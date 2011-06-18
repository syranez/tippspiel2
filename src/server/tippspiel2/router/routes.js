var resource = require('resource-router');

function routes (app) {
  app.resource('/', {
    'get' : function(req, res) {
      console.log('here');
      res.writeHead(200, {
        'Content-Type': 'text/html'
      });
      res.end('Hello world', 'utf8');
    }
  });
};

exports.routes = resource(routes);
