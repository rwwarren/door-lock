var express = require('express');
var React = require('react');
var Router = require('react-router');

var app = express();

app.set('views', __dirname + '/views');
app.set('view engine', 'jade');
app.use(express.static(__dirname + '/../root'));

app.get('/*', function (req, res) {
  res.render('index')
});

var serverPort = 3000;
var server = app.listen(serverPort, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});
