import _ from 'underscore';
import Backbone from 'backbone';
import Config from './Config';

const Model = Backbone.Model.extend({
  parse(response, opts) {
    if (response.data) {
      return response.data;
    }
    return response;
  }
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
      //Toastr.remove();
      //Toastr.error(msg);
      //this.context.toastr.addAlertError(msg);
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
          //message: 'Unexpected server error '+opts.status+' '+opts.statusText+': '+opts.responseText
          message: 'O servidor não conseguiu processar sua solicitação. Tente novamente mais tarde. Se o erro persistir contate o suporte.'
        };
      }
      this.trigger('fail', resp.message);
      //this.context.toastr.addAlertError(resp.message);
    } else if (opts.status === 409) {
      // Validation errors
      try {
        let resp = JSON.parse(opts.responseText);
      } catch (err) {
        resp = {
          //message: 'Unexpected server error '+opts.status+' '+opts.statusText+': '+opts.responseText
          message: 'O servidor não pode concluir sua ação devido a um conflito na execução do mesmo. Tente novamente mais tarde. Se o erro persistir contate o suporte.'
        };
      }
      this.trigger('fail', resp.message);
      //this.context.toastr.addAlertError(resp.message);
    } else if (opts.status === 403) {
      // Probably lost the session
      UserSession.dispatch({
        action: UserSession.ACTION_LOGOUT
      });
      this.trigger('fail', 'Não foi possível concluir sua ação. Sua sessão foi encerrada.');
      this.trigger('forbidden');
    } else if (opts.status === 401) {
      // Probably lost the session
      //this.trigger('fail', 'Não foi possível concluir sua ação. Refaça o login no sistema e tente novamente. Se o erro persistir contate o suporte.');
      this.trigger('unauthorized');
    } else {
      //this.trigger('fail', 'Unexpected server error '+opts.status+' '+opts.statusText+': '+opts.responseText);
      this.trigger('fail', 'Não foi possível concluir sua ação. Tente novamente mais tarde. Se o erro persistir contate o suporte.');
      //this.addAlertError('Unexpected server error '+opts.status+' '+opts.statusText+': '+opts.responseText);
    }
  },

});

export default {
  BACKEND_URL: Config.baseUrl,
  Model: Model,
  Store: Store,
};
