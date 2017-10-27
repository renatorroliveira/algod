import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const DisciplineModel = Fluxbone.Model.extend({

});

const DisciplineStore = Fluxbone.Store.extend({
  ACTION_CREATE: 'create',
  ACTION_LIST: 'list',
  ACTION_LIST_CATEGORYS: 'listCategorys',
  ACTION_DELETE: 'delete',
  ACTION_GET_DISCIPLINE: 'getDiscipline',

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

  getDiscipline(params) {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/getDiscipline`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger('getDiscipline', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

});

export default new DisciplineStore();
