import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const InstitutionModel = Fluxbone.Model.extend({

});

const InstitutionStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',
  ACTION_LIST: 'listInsti',
  ACTION_DELETE: 'deleteInstitution',

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
        me.trigger(me.ACTION_REGISTER, data);
      },
      error(args) {
        me.trigger('fail', `Erro inesperado: ${args}`);
      },
    });
  },

  listInsti() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/listAll`,
      dataType: 'json',
      success(data) {
        me.trigger(me.ACTION_LIST, data);
      },
      error(errs) {
        me.trigger('fail', errs);
      },
    });
  },

  deleteInstitution(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/delete`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger(me.ACTION_DELETE, data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },
});

export default new InstitutionStore();
