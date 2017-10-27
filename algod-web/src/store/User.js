import $ from 'jquery';
import Fluxbone from './Fluxbone';

const UserModel = Fluxbone.Model.extend({

});

const UserStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',
  ACTION_LIST: 'list',

  model: UserModel,
  url: `${Fluxbone.baseUrl}/v1/user`,

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

  register(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/register`,
      dataType: 'json',
      data: JSON.stringify({
        user: params,
        deviceId: localStorage.deviceId,
      }),
      success(data) {
        me.trigger('register', data);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },

  list() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/list`,
      dataType: 'json',
      success(data) {
        me.trigger('list', data);
      },
      error(opts) {
        me.handleRequestErrors([], opts);
      },
    });
  },

});

export default new UserStore();
