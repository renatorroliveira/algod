import $ from 'jquery';

$.ajaxSetup({
  headers: {
    'Content-Type': 'application/json',
  },
});

export default {
  baseUrl: 'http://localhost:3000/api/',
};
