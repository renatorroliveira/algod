import $ from 'jquery';

$.ajaxSetup({
  xhrFields: {
    withCredentials: true,
  },
  crossDomain: true,
  contentType: 'application/json',
  processData: false,
});

export default {
  baseUrl: 'http://localhost:8000/algod/api',
};
