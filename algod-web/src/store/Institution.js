import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const InstitutionModel = Fluxbone.Model.extend({

});

const InstitutionStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',
  ACTION_LIST: 'list',
  ACTION_DELETE: 'delete',

  model: InstitutionModel,
  url: `${Config.baseUrl}/v1/institution`,

  register(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/register`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('register', data);
      },
      error(opts) {
        me.trigger('fail', opts);
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
        me.trigger('fail', opts);
      },
    });
  },

  delete(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/delete`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('delete', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },
});

export default new InstitutionStore();
