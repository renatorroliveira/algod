import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const TopicModel = Fluxbone.Model.extend({

});

const TopicStore = Fluxbone.Store.extend({
  ACTION_LIST_TOPICS: 'listTopics',
  ACTION_LIST_TOPIC_ITEMS: 'listTopicItems',
  ACTION_ADD_TOPIC: 'addTopic',
  ACTION_DELETE_TOPIC: 'deleteTopic',
  ACTION_ADD_TOPIC_ITEM: 'addTopicItem',
  ACTION_GET_TOPIC_ITEM: 'getTopicItemById',
  ACTION_UPLOAD: 'uploadFile',

  model: TopicModel,
  url: `${Config.baseUrl}/v1/topic`,

  addTopic(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/create`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        id: params.discipline.id,
        title: params.title,
      }),
      success(response) {
        if (response.success) {
          me.trigger('addTopic', response.data);
        } else {
          me.trigger('fail', response.message);
        }
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  deleteTopic(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/del/${params}`,
      method: 'POST',
      dataType: 'json',
      success(data) {
        me.trigger('deleteTopic', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  addTopicItem(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/${params.id}/item/add`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        topicItem: params.topicItem,
      }),
      success(data) {
        me.trigger('addTopicItem', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  listTopics(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/${id}`,
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

  listTopicItems(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/${id}/items`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('listTopicItems', data);
      },
      error(opts) {
        me.trigger('fail', opts);
      },
    });
  },

  getTopicItemById(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/item/${id}`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('getTopicItemById', data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  uploadFile(params) {
    console.log(params.file);
    const me = this;
    $.ajax({
      url: `${me.url}/task/${params.topicItem.id}/upload`,
      type: 'POST',
      data: {
        file: params.file,
      },
      async: true,
      cache: false,
      contentType: 'multipart/form-data',
      success(response) {
        console.log(response);
      },
      error(e) {
        console.error(e);
      },
    });

    // $.ajax({
    //   type: 'POST',
    //   url: `${me.url}/task/${params.topicItem.id}/upload`,
    //   xhr() {
    //     const myXhr = $.ajaxSettings.xhr();
    //     if (myXhr.upload) {
    //       myXhr.upload.addEventListener('progress', me.progressHandling, false);
    //     }
    //     return myXhr;
    //   },
    //   async: true,
    //   data: {
    //     file: params.file,
    //   },
    //   cache: false,
    //   contentType: false,
    //   processData: false,
    //   timeout: 60000,
    //   success(data) {
    //     // your callback here
    //     console.log(data);
    //   },
    //   error(error) {
    //     // handle error
    //     console.log(error);
    //   },
    // });
  },

});

export default new TopicStore();
