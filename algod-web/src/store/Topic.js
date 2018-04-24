import axios from 'axios';
import $ from 'jquery';
import Fluxbone from './Fluxbone';
import Config from './Config';

const TopicModel = Fluxbone.Model.extend({

});

const TopicStore = Fluxbone.Store.extend({
  ACTION_ADD_TOPIC: 'createTopic',
  ACTION_DELETE_TOPIC: 'deleteTopic',
  ACTION_ADD_TOPIC_ITEM: 'addTopicItem',
  ACTION_DELETE_TOPIC_ITEM: 'deleteTopicItem',
  ACTION_LIST_TOPICS: 'listTopics',
  ACTION_LIST_TOPIC_ITEMS: 'listTopicItems',
  ACTION_GET_TOPIC_ITEM: 'getTopicItemById',
  ACTION_UPLOAD: 'uploadSend',
  ACTION_UPLOAD_MULTIPLE: 'uploadSendMultipleFiles',
  ACTION_DOWNLOAD: 'downloadSend',
  ACTION_SENDS: 'listAllSends',
  ACTION_SENDS_DOWNLOAD: 'downloadAllSends',
  ACTION_GET_SEND: 'getSend',
  ACTION_DOWNLOAD_USER_FILE: 'downloadUserFile',
  ACTION_GET_EVALUATIONS: 'getAllEvaluations',
  ACTION_SEND_EVALUATION: 'sendEvaluation',
  ACTION_GET_EVALUATION: 'getEvaluation',

  model: TopicModel,
  url: `${Config.baseUrl}/v1/topic`,

  createTopic(params) {
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
      url: `${me.url}/${params}/del`,
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

  deleteTopicItem(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/${id}/item/del`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('delTopicItem', data);
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

  uploadSend(params) {
    const me = this;
    const promisse = axios.post(
      `${this.url}/task/${params.topicItem.id}/send`,
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
        if (status === 'success') {
          me.trigger('successDownload', response, xhr);
        }
      },
      error(err) {
        console.error(err);
        me.trigger('failDownload', err);
      },
    });
  },

  downloadUserFile(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${params.topicItem.id}/download/user/${params.user.id}`,
      method: 'GET',
      xhrFields: {
        responseType: 'blob',
      },
      success(response, status, xhr) {
        if (status === 'success') {
          me.trigger('successDownload', response, xhr);
        }
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

  downloadAllSends(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/sends/downloads`,
      method: 'GET',
      xhrFields: {
        responseType: 'arraybuffer',
      },
      success(response, status, xhr) {
        if (status === 'success') {
          me.trigger('successDownloadZip', response, xhr);
        }
      },
      error(err) {
        console.error(err);
        me.trigger('failDownload', err);
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

  getUnsend(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/unsend/loggedUser`,
      method: 'GET',
      dataType: 'json',
      success(response) {
        me.trigger('getUnsend', response.data);
      },
      error(args) {
        me.trigger('fail', args);
      },
    });
  },

  sendEvaluation(params) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${params.topicItem.id}/user/evaluation`,
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        avaliation: params.avaliation,
        user: params.user,
      }),
      success(data) {
        console.log(data);
        me.trigger('evaluated', data.data);
      },
      error(err) {
        console.error(err);
      },
    });
  },

  getEvaluation(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/evaluation`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('evaluation', data.data);
      },
      error(err) {
        console.error(err);
      },
    });
  },

  getAllEvaluations(id) {
    const me = this;
    $.ajax({
      url: `${me.url}/task/${id}/evaluations`,
      method: 'GET',
      dataType: 'json',
      success(data) {
        me.trigger('evaluations', data.data);
      },
      error(err) {
        console.error(err);
      },
    });
  },

});

export default new TopicStore();
