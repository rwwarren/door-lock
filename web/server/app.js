var express = require('express');
var cookieParser = require('cookie-parser');
var React = require('react');
var Router = require('react-router');
var Request = require('request');

var app = express();

app.set('views', __dirname + '/../src/views');
app.set('view engine', 'jade');
app.use(cookieParser());
app.use(express.static(__dirname + '/../src/root'));

app.get('/', function(req, res) {
  res.render('index')
});

app.get('/admin', function(req, res) {
  res.render('index')
});

app.get('/user', function(req, res) {
  res.render('index')
});

app.get('/lock', function(req, res) {
  res.render('index')
});

app.get('/config', function(req, res) {
  var common = require('../src/inc/Common');
  var url = common.API_URL + common.CONFIG;
  var sid = req.cookies.sid;
  if(sid != null) {
    Request.post(url, {json: {sid: sid}}, function(err, resp, body) {
      if(err == null && resp.statusCode === 200) {
        res.send(body);
      } else {
        res.redirect('/');
      }
    });
  } else {
    res.redirect('/');
  }
});

app.get('*', function(req, res) {
  res.status(404) 
    .send('Not found. <a href="/">Back to home.</a>');
});

var serverPort = process.env.PORT || 3000;
var serverAddress = process.env.ADDRESS || 'localhost';
var server = app.listen(serverPort, serverAddress, function() {
  var host = server.address().address;
  var port = server.address().port;
  console.log(server.address());
  console.log('Doorlock web app listening at http://%s:%s', host, port);
});
