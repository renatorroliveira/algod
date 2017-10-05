import Toastr from 'toastr';
import $ from 'jquery';
import Fluxbone from './Fluxbone';

const InstitutionModel = Fluxbone.Model.extend({

});

const InstitutionStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',

  model: InstitutionModel,
  url: `${Fluxbone.baseUrl}/v1/institution`,

  register(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/register`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        if (data.success) {
          me.trigger(data, me);
          Toastr.success('Nova instituição adicionada');
        } else {
          Toastr.error('Erro inesperado ao criar instituição');
        }
      },
      error(args) {
        console.log(args);
        Toastr.error(`${args.responseJSON.error}`);
        me.trigger('fail', `Erro inesperado: ${args}`, me);
      },
    });
  },
});

export default new InstitutionStore();
