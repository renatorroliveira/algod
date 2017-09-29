import $ from 'jquery';
import Fluxbone from './Fluxbone';

const UserModel = Fluxbone.Model.extend({

});

const UserStore = Fluxbone.Store.extend({
  ACTION_REGISTER: 'register',

  model: UserModel,
  url: `${Fluxbone.baseUrl}/v1/user`,

  register(params) {
    const me = this;
    console.log('registro:', params);
    $.ajax({
      method: 'POST',
      url: `${me.url}/register`,
      data: JSON.stringify({
        user: params,
        deviceId: localStorage.deviceId,
      }),
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
