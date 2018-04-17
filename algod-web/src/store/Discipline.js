import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const DisciplineModel = Fluxbone.Model.extend({

});

const DisciplineStore = Fluxbone.Store.extend({
  ACTION_CREATE: 'create',
  ACTION_LIST_SUBSCRIBED_DISCIPLINES: 'listSubscribedDisciplines',
  ACTION_LIST_ALL: 'listAll',
  ACTION_LIST_CATEGORYS: 'listCategorys',
  ACTION_DELETE: 'delete',
  ACTION_GET_DISCIPLINE: 'getDiscipline',
  ACTION_GET_SUBSCRIPTION: 'getSubscription',
  ACTION_SUBSCRIBE: 'doSubscribe',
  ACTION_UNSUBSCRIBE: 'doUnsubscribe',
  ACTION_SEARCH: 'search',
  ACTION_GET_SUBSCRIBED_USERS: 'subscribedUsers',
  ACTION_SUBSCRIBE_USER: 'subscribeUser',
  ACTION_UPDATE_ROLE: 'updateUserRole',
  ACTION_UNSUBSCRIBE_USER: 'unsubscribeUser',

  model: DisciplineModel,
  url: `${Config.baseUrl}/v1/discipline`,

  create(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/create`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('create', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  listSubscribedDisciplines() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/list`,
      dataType: 'json',
      success(data) {
        me.trigger('listSubscribedDisciplines', data);
      },
      error(opts) {
        me.trigger('fail', opts);
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
        me.trigger('listAll', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  listCategorys() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/category/list`,
      dataType: 'json',
      success(data) {
        me.trigger('listCategorys', data);
      },
      error(errs) {
        me.trigger('fail', errs);
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

  getDiscipline(id) {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/${id}`,
      dataType: 'json',
      success(response) {
        me.trigger('getDiscipline', response.data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  getSubscription(id) {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/${id}/subscription`,
      dataType: 'json',
      success(response) {
        me.trigger('getSubscription', response.data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  doSubscribe(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/${params.id}/subscribe`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(response) {
        console.log(response);
        me.trigger('doSubscribe', response.data);
      },
      error(opts) {
        console.error(opts);
        me.trigger('fail', opts);
      },
    });
  },

  doUnsubscribe(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/unsubscribe`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(response) {
        me.trigger('doUnsubscribe', response.data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  search(terms) {
    const me = this;
    $.ajax({
      url: `${me.url}/search/${terms}`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('search', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  subscribedUsers(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/${params}/users`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('subscribedUsers', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  subscribeUser(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/${params.discipline.id}/subscribeUser`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        user: params.user,
      }),
      success(data) {
        me.trigger('subscribeUser', data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  updateUserRole(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/${params.discipline.id}/updateUserRole`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        user: params.user,
        newRole: params.newRole,
      }),
      success(data) {
        me.trigger('updateUserRole', data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  unsubscribeUser(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/${params.discipline.id}/unsubscribeUser`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        user: params.user,
      }),
      success(data) {
        me.trigger('unsubscribeUser', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

});

export default new DisciplineStore();
