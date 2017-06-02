'use strict';

module.exports = function(app) {
  app.dataSources.mysqlDs.automigrate('Institution', function(err) {
    if (err) throw err;

    app.models.Institution.create([{
      name: 'AlGod',
    }, {
      name: 'Informática para a Internet',
      image: '',
	  description: 'Curso de informática para a internet\
	  	do IFC Campus Concórdia',
    }], function(err, institutions) {
      if (err) throw err;

      console.log('Models created: \n', institutions);
    });
  });
};
