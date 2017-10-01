/* eslint no-console: ["error", { allow: ["warn", "error"] }] */
import 'toastr/build/toastr.css';
import Toastr from 'toastr';
import Backbone from 'backbone';
import Config from './Config';
import UserSession from './UserSession';

const Model = Backbone.Model.extend({
  parse(response) {
    if (response.data) {
      return response.data;
    }
    return response;
  },
});

const Store = Backbone.Collection.extend({
  BACKEND_URL: Config.baseUrl,
  dispatch(payload) {
    if (payload.action) {
      const method = payload.action;
      if (method) {
        const callback = this[method];
        if (typeof callback === 'function') {
          try {
            callback.call(this, payload.data, payload.pigback);
          } catch (err) {
            console.error('Store: Dispatching', payload.action, 'failed:', err);
          }
        } else {
          console.warn('Store: Dispatch action is not a function:', (typeof callback), 'for method', method);
        }
      } else {
        console.warn('Store: Invalid dispatch action:', payload.action);
      }
    }
  },
  initialize() {
    this.on('error', this.handleRequestErrors, this);
    this.on('fail', (msg) => {
      if (msg.error) {
        Toastr.error(msg.error.message);
      } else {
        Toastr.error('Um erro inesperado ocorreu.');
        console.error(msg);
      }
    }, this);
  },
  parse(response) {
    this.total = response.total;
    return response.data;
  },
  handleRequestErrors(collection, opts) {
    if (opts.status === 400) {
      // Validation errors
      let resp;
      try {
        resp = JSON.parse(opts.responseText);
      } catch (err) {
        resp = {
          message: 'O servidor não conseguiu processar sua solicitação.',
        };
      }
      this.trigger('fail', resp.message);
    } else if (opts.status === 409) {
      // Validation errors
      let resp;
      try {
        resp = JSON.parse(opts.responseText);
      } catch (err) {
        resp = {
          message: 'O servidor não pode concluir sua ação.',
        };
      }
      this.trigger('fail', resp.message);
    } else if (opts.status === 403) {
      UserSession.dispatch({
        action: UserSession.ACTION_LOGOUT,
      });
      this.trigger('fail', 'Não foi possível concluir sua ação. Sua sessão foi encerrada.');
      this.trigger('forbidden');
    } else if (opts.status === 401) {
      this.trigger('unauthorized');
    } else {
      this.trigger('fail', 'Não foi possível concluir sua ação. Tente novamente mais tarde. Se o erro persistir contate o suporte.');
    }
  },

});

export default {
  baseUrl: Config.baseUrl,
  Model,
  Store,
};
