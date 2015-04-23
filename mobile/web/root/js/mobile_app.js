/**
 * PiDuinoLock mobile web app
 */
"use strict";

var API_URL = "http://m.localhost/";
//var API_URL = "http://api.localhost/";
var LOGIN = "login.php";
var sid = 'adf';

var Nav = React.createClass({
  render: function(){
    if($.cookie("sid") == null) {
      $.cookie("sid", makeid());
    }
    if(!this.props.isLoggedIn){
      return null;
    }
    return (
      <div>Loggedin asdf</div>
    );
  },
});

var LoginScreen = React.createClass({
  render: function(){
    return (
      <div className="login">
        <input ref="username" id="username" className="textbox" placeholder="username" />
        <input ref="password" id="password" type="password" className="textbox" placeholder="password" />
        <input ref="token" id="token" className="textbox" placeholder="token" />
        <button id="submit" type="button" onClick={this.attemptLogin}>Login</button>
      </div>
    );
  },
  attemptLogin: function() {
    var username = this.refs.username.getDOMNode().value.trim();
    var password = this.refs.password.getDOMNode().value.trim();
    var token = this.refs.token.getDOMNode().value.trim();
    var data = {Username: username, Password: password, Token: token};
    //console.log(data);
    //var posting = $.post(API_URL + LOGIN, data, function(result) {
    //  //this.getAll(this.state.username);
    //  console.log(result);
    //}.bind(this));
    //var posting = $.ajax({
    //  url: API_URL + LOGIN,
    //  type: 'post',
    //  method: "POST",
    //  data: data,
    //  dataType: "jsonp",
    //  headers: {
    //    'Access-Control-Allow-Origin': 'true',
    //    'x-doorlock-api-key': 'test',
    //    'sid': sid,
    //  },
    //  success: function (data) {
    //    console.info(data);
    //  },
    //  //this.getAll(this.state.username);
    //  //console.log(result);
    //});
    $.ajax({
            url: API_URL + LOGIN,
            type: "POST",
            //crossDomain: true,
            data: data,
            dataType: "json",
            //beforeSend: function(xhr) {
            //      xhr.setRequestHeader("sid", "session=xxxyyyzzz");
            //      xhr.setRequestHeader("x-doorlock-api-key", "test");
            //},
            //headers: {
            //  'x-doorlock-api-key': 'test',
            //  'sid': 'sid',
            //},
            success:function(result){
                //console.log(result);
                //console.log(JSON.stringify(result));
                this.props.loginChange();
                //this.setState({loggedIn: true});
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
        });
        //}.bind(this));
    //}.bind(this));
    //var posting = $.ajax({
    //    method: "POST",
    //    type: "POST",
    //    //crossDomain: true,
    //    //dataType: 'jsonp',
    //    url: API_URL + LOGIN,
    //    data: data,
    //    beforeSend: function(xhr){
    //      xhr.setRequestHeader("Content-Type","application/json");
    //      xhr.setRequestHeader("Accept","application/json");
    //    },
    //    headers: {
    //      //'Access-Control-Allow-Origin': true,
    //      'x-doorlock-api-key': 'test',
    //      'sid': sid,
    //    }
    //})
    //.done(function( msg ) {
    //  console.log(msg);
    //  //alert( "Data Saved: " + msg );
    //}.bind(this));
  },
});

var UserContent = React.createClass({
  render: function(){
    return (
      <div>Loggedin User Content</div>
    );
  },
});

var MobileWebDoorlock = React.createClass({
  getInitialState: function(){
    return {
      loggedIn: false,
    };
  },
  login: function() {
    this.setState({loggedIn: true});
  },
  checkLogin: function() {
   return true; 
  },
  checkLoggedIn: function() {
    return this.state.loggedIn && this.checkLogin();
  },
  render: function() {
      var now = new Date
      var theYear=now.getYear()
      if (theYear < 1900) {
        theYear=theYear+1900
      }
            //{this.state.loggedIn ? <UserContent />: <LoginScreen loginChange={this.login} />}
    return (
      <div className="container">
        <div className="header">
        </div>
        <div className="body">
          <div className="navigation">
            <Nav isLoggedIn={this.state.loggedIn} />
          </div>
          <div className="content">
            {this.checkLoggedIn() ? <UserContent />: <LoginScreen loginChange={this.login} />}
          </div>
        </div>
        <div className="footer">
          &copy; {theYear} PiDuinoLock Mobile Web Interface
        </div>
      </div>
    );
  },
});
function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for( var i = 0; i < 103; i++ ) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
}

React.render(<MobileWebDoorlock />, document.body);
