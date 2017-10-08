import $ from 'jquery';
import Backbone from 'backbone';
import string from 'string';
import Config from './Config';

const UserSession = Backbone.Model.extend({
  ACTION_REFRESH: 'refreshStatus',
  ACTION_LOGIN: 'login',
  ACTION_LOGOUT: 'logout',
  ACTION_RECOVER_PASSWORD: 'recoverPass',
  ACTION_VALIDATE_TOKEN: 'canResetPass',
  ACTION_NEW_PASSWORD: 'newPassword',
  ACTION_PICTURE: 'changePic',

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
        location.assign('#/');
      },
    });
  },

  handleRequestErrors(collection, opts) {
    if (opts.status === 400) {
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
        location.assign('#/auth/login');
        me.trigger('logout');
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
  changePic(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/picture`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('changePic', data);
      },
      error(args) {
        me.handleRequestErrors([], args);
      },
    });
  },
  recoverPass(email) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/recover-password`,
      dataType: 'json',
      data: JSON.stringify(email),
      success() {
        me.trigger('recoverPass', me);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
  canResetPass(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/recover-password/${params.token}`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        if (data.success) {
          me.set({
            ValidToken: true,
          });
          me.trigger(me.ACTION_VALIDATE_TOKEN);
        }
      },
      error(args) {
        me.handleRequestErrors([], args);
        me.set({
          ValidToken: null,
        });
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
        me.trigger(me.ACTION_NEW_PASSWORD);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },
});

export default new UserSession();
