import $ from 'jquery';
import Fluxbone from './Fluxbone';

const UserModel = Fluxbone.Model.extend({

});

const UserStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',

  model: UserModel,
  url: `${Fluxbone.baseUrl}AlgodUsers`,

  register(params) {
    const me = this;
    console.log('registro:', params);
    $.ajax({
      method: 'POST',
      url: me.url,
      data: JSON.stringify(params),
      success(data) {
        me.trigger(me.ACTION_REGISTER, data);
      },
      error(res) {
        me.trigger('fail', res.responseJSON);
      },
    });
  },
});

export default new UserStore();
