import $ from 'jquery';

$.ajaxSetup({
  contentType: 'application/json',
  processData: false,
});

export default {
  baseUrl: 'http://localhost:8000/api',
};
