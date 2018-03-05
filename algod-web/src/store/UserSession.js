import $ from 'jquery';
import Backbone from 'backbone';
import string from 'string';
import Config from './Config';

const UserSession = Backbone.Model.extend({
  ACTION_REFRESH: 'refreshStatus',
  ACTION_LOGIN: 'login',
  ACTION_LOGOUT: 'logout',
  ACTION_RECOVER_REQUEST: 'recoverReq',
  ACTION_VALIDATE_TOKEN: 'tokenValidation',
  ACTION_NEW_PASSWORD: 'newPassword',
  ACTION_CHANGE_PIC: 'changePicture',
  ACTION_GET_USER: 'getUser',
  ACTION_LIST_USERS_BY_NAME: 'paginatedList',

  url: `${Config.baseUrl}/v1/user`,
  dispatch(payload) {
    if (payload.action) {
      const method = this[payload.action];
      if (typeof method === 'function') {
        method.call(this, payload.data, payload.pigback);
      } else {
        console.warn(`UserSession: The action (method) ${payload.action} is not defined.`);
      }
    } else {
      console.warn('UserSession: The dispatching action must be defined.\n', payload);
    }
  },
  initialize() {
    if (string(localStorage.deviceId).isEmpty()) {
      localStorage.deviceId = `${navigator.userAgent}---${(new Date()).getTime()}`;
    }
    if (!(string(localStorage.token).isEmpty())) {
      $.ajaxSetup({
        headers: {
          Authorization: localStorage.token,
        },
      });
      this.set({
        loading: true,
      });
      this.refreshStatus(true);
    } else {
      this.set({
        loading: false,
      });
    }
  },
  parse(response) {
    return response.data ? {
      logged: true,
      user: response.data.user,
      accessLevel: response.data.accessLevel,
      permissions: response.data.permissions,
    } : {
      logged: false,
    };
  },

  clearStorage() {
    localStorage.removeItem('token');
    localStorage.removeItem('userId');
  },
  putStorage(token, userId) {
    localStorage.token = token;
    localStorage.userId = userId;
  },

  refreshStatus() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/logged`,
      dataType: 'json',
      success(data) {
        if (data.success) {
          me.putStorage(data.data.token, data.data.user.id);
          $.ajaxSetup({
            headers: {
              Authorization: localStorage.token,
            },
          });
          me.set({
            logged: true,
            user: data.data.user,
            tenant: data.data.tenant,
            accessLevel: data.data.accessLevel,
            permissions: data.data.permissions,
          });
          me.trigger('login', me);
        } else {
          me.trigger('fail', data.message);
        }
        if (me.get('loading')) {
          me.set({
            loading: false,
          });
          me.trigger('loaded', true);
        }
      },
      error() {
        if (me.get('loading')) {
          me.set({
            loading: false,
          });
          me.trigger('loaded', true);
        }
        me.clearStorage();
        location.assign('#/auth/login');
      },
    });
  },

  handleRequestErrors(collection, opts) {
    if (opts.status === 504) {
      this.trigger('fail', opts.statusText);
    } else if (opts.status === 404) {
      this.trigger('fail', opts);
    } else if (opts.status === 400) {
      this.trigger('fail', opts.responseJSON.message);
    } else if (opts.status === 409) {
      // Validation errors
      let resp = {};
      try {
        resp = JSON.parse(opts.responseText);
      } catch (err) {
        resp = {
          message: `Unexpected server error ${opts.status} ${opts.statusText}: ${opts.responseText}`,
        };
      }
      this.trigger('fail', resp.message);
    } else {
      this.trigger('fail', `Unexpected server error ${opts.status} ${opts.statusText}: ${opts.responseJSON.message}`);
    }
  },

  login(params) {
    const me = this;
    const errors = [];

    if (string(params.email).isEmpty() || string(params.password).isEmpty()) {
      errors.push('Por favor, digite seu nome de usuário e sua senha');
    }

    if (errors.length > 0) {
      me.trigger('fail', errors);
    } else {
      $.ajax({
        method: 'POST',
        url: `${me.url}/login`,
        dataType: 'json',
        data: JSON.stringify(params),
        success(data) {
          if (data.success) {
            $.ajaxSetup({
              headers: {
                Authorization: data.data.token,
              },
            });
            me.putStorage(data.data.token, data.data.user.id);
            me.set({
              logged: true,
              user: data.data.user,
              tenant: data.data.tenant,
              accessLevel: data.data.accessLevel,
              permissions: data.data.permissions,
            });
            me.trigger('login', me);
          } else {
            me.trigger('fail', data.message);
          }
        },
        error(opts) {
          me.handleRequestErrors([], opts);
        },
      });
    }
  },
  logout() {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/logout`,
      dataType: 'json',
      success() {
        me.set({
          logged: false,
          user: null,
        });
        $.ajaxSetup({
          headers: {
            Authorization: null,
          },
        });
        me.clearStorage();
        me.trigger('logout');
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
  changePicture(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/picture`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('changePicture', data);
      },
      error(args) {
        me.handleRequestErrors([], args);
      },
    });
  },
  recoverReq(email) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/recover-password`,
      dataType: 'json',
      data: JSON.stringify(email),
      success(data) {
        me.trigger('recoverReq', data);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
  tokenValidation(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/recover-password/${params.token}`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('tokenValidation', data);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
  newPassword(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/reset-password/${params.token}`,
      dataType: 'json',
      data: JSON.stringify(params),
      success() {
        me.trigger('newPassword');
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },

  getUser(nickname) {
    const me = this;
    $.ajax({
      url: `${me.url}/profile/${nickname}`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('getUser', data);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },

  paginatedList(name) {
    console.log(name);
    //
  },

});

export default new UserSession();
