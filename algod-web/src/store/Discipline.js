import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const DisciplineModel = Fluxbone.Model.extend({

});

const DisciplineStore = Fluxbone.Store.extend({
  ACTION_ADD: 'new',
  ACTION_LIST: 'list',
  ACTION_DELETE: 'delete',
  ACTION_LIST_ALL: 'listAll',

  model: DisciplineModel,
  url: `${Config.baseUrl}/v1/discipline`,

  new(params) {
    const me = this;
    $.ajax({
      method: 'POST',
      url: `${me.url}/create`,
      dataType: 'json',
      data: JSON.stringify(params),
      success(data) {
        me.trigger(me.ACTION_ADD, data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  list() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/category/list`,
      dataType: 'json',
      success(data) {
        me.trigger(me.ACTION_LIST, data);
      },
      error(errs) {
        me.trigger('fail', errs);
      },
    });
  },

  listAll() {
    const me = this;
    $.ajax({
      method: 'GET',
      url: `${me.url}/list`,
      dataType: 'json',
      success(data) {
        me.trigger(me.ACTION_LIST_ALL, data);
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
        me.trigger(me.ACTION_DELETE, data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },
});

export default new DisciplineStore();
