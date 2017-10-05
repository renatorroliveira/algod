import Toastr from 'toastr';
import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const InstitutionModel = Fluxbone.Model.extend({

});

const InstitutionStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',
  ACTION_LIST_ALL: 'listAll',
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
        if (data.success) {
          me.trigger(data);
          Toastr.success('Nova instituição adicionada');
        } else {
          Toastr.error('Erro inesperado ao criar instituição');
        }
      },
      error(args) {
        me.trigger('fail', `Erro inesperado: ${args}`);
        Toastr.error(args);
      },
    });
  },

  listAll() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/listAll`,
      dataType: 'json',
      success(data) {
        console.log(data);
        me.trigger(data, me);
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
        me.trigger(data, me);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },
});

export default new InstitutionStore();
