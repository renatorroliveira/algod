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
  ACTION_UPLOAD: 'uploadSend',
  ACTION_DOWNLOAD: 'downloadSend',
  ACTION_SENDS: 'listAllSends',
  ACTION_SENDS_DOWNLOAD: 'downloadAllSends',
  ACTION_GET_SEND: 'getSend',

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

  getSend(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/send/loggedUser`,
      method: 'GET',
      dataType: 'json',
      success(response) {
        me.trigger('getSend', response.data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  uploadSend(params) {
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

  downloadSend(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${params.topicItem.id}/download`,
      method: 'GET',
      xhrFields: {
        responseType: 'blob',
      },
      success(response, status, xhr) {
        const contentType = xhr.getResponseHeader('content-type');
        const filename = xhr.getResponseHeader('filename');
        const blob = new Blob([response], { type: contentType }, filename);
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        me.trigger('successDownload');
      },
      error(err) {
        console.error(err);
        me.trigger('failDownload', err);
      },
    });
  },

  downloadAllSends(id) {
    console.log(id);
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/sends/downloads`,
      method: 'GET',
      xhrFields: {
        responseType: 'arraybuffer',
      },
      success(response, status, xhr) {
        console.log(status);
        me.trigger('successSendsDownloads', response, xhr);
      },
      error(err) {
        console.error(err);
        me.trigger('failDownload', err);
      },
    });
  },

  listAllSends(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/sends`,
      method: 'GET',
      dataType: 'json',
      success(response) {
        me.trigger('sends', response.data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

});

export default new TopicStore();
