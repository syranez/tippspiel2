var connect = require("connect");
var routes  = require("./router/routes");

// Serverport
var port = 9876;

exports.tippspiel2 = function tippspiel2 () {
    var server = connect.createServer();
    server.use(routes.routes)
    server.listen(port);
    console.log('Connect server listening on port ' + port);
}
