import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const DisciplineModel = Fluxbone.Model.extend({

});

const DisciplineStore = Fluxbone.Store.extend({
  ACTION_CREATE: 'create',
  ACTION_LIST: 'list',
  ACTION_LIST_ALL: 'listAll',
  ACTION_LIST_CATEGORYS: 'listCategorys',
  ACTION_DELETE: 'delete',
  ACTION_GET_DISCIPLINE: 'getDiscipline',
  ACTION_GET_SUBSCRIPTION: 'getSubscription',
  ACTION_SUBSCRIBE: 'doSubscribe',
  ACTION_UNSUBSCRIBE: 'doUnsubscribe',
  ACTION_SEARCH: 'search',
  ACTION_LIST_TOPICS: 'listTopics',
  ACTION_ADD_TOPIC: 'addTopic',

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
    console.log(`id: ${id}`);
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
        me.trigger('doSubscribe', response.data);
      },
      error(opts) {
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

  listTopics(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/topics/${id}`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('listTopics', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  addTopic(params) {
    const me = this;
    console.log(params);
    $.ajax({
      url: `${me.url}/topic/add`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('addTopic', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

});

export default new DisciplineStore();
