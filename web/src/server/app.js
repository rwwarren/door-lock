var express = require('express');
var React = require('react');
var Router = require('react-router');

var app = express();

app.set('views', __dirname + '/views');
app.set('view engine', 'jade');
app.use(express.static(__dirname + '/../root'));

app.get('/', function (req, res) {
  res.render('index')
});

app.get('/admin', function (req, res) {
  res.render('index')
});

app.get('/user', function (req, res) {
  res.render('index')
});

app.get('/lock', function (req, res) {
  res.render('index')
});

app.get('/config', function (req, res) {
  //res.render('index')
});

app.get('*', function (req, res) {
  res.status(404)        // HTTP status 404: NotFound
    .send('Not found');
});

var serverPort = 3000;
var server = app.listen(serverPort, function () {
  var host = server.address().address;
  var port = server.address().port;

  console.log('Example app listening at http://%s:%s', host, port);
});
