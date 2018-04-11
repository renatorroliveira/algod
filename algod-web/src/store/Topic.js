import axios from 'axios';
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
  ACTION_DOWNLOAD: 'download',

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
    const me = this;
    const promisse = axios.post(
      `${this.url}/task/${params.topicItem.id}/upload`,
      params.formData,
    );
    promisse.then((result) => {
      console.log(result);
      if (result.data.success) {
        me.trigger('successUpload');
      }
    }).catch((error) => {
      console.error(error);
      me.trigger('fail', error);
    });
  },

  download(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${params.topicItem.id}/download`,
      method: 'GET',
      responseType: 'blob',
      dataType: 'json',
      success(response, status, xhr) {
        console.log(xhr);
        xhr.catch((error) => {
          me.trigger('failDownload', error.responseJSON.message);
        });
        console.log(status);
        console.log(response);
        console.log(xhr.getAllResponseHeaders());

        const contentType = xhr.getResponseHeader('content-type');
        const filename = xhr.getResponseHeader('filename');

        const blob = new Blob([response], { type: contentType }, filename);
        console.log(blob);
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
      },
      error(err) {
        console.error(err);
        me.trigger('failDownload', err.responseJSON.message);
      },
    });
  },

});

export default new TopicStore();
