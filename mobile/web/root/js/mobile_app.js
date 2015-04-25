/**
 * PiDuinoLock mobile web app
 */
"use strict";

var API_URL = "http://m.localhost/";
//var API_URL = "http://api.localhost/";
var LOGIN = "login.php";
var CHECK_LOGIN = "checklogin.php";
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
      <div className="navigation">
        <a className="links" href="/">Home</a>
         |
        <a className="links" href="/users">User Info</a>
         |
        <a className="links" href="/lock">Lock Status</a>
         |
        <a className="links" href="/logout">Logout</a>
      </div>
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
    var data = {Username: username, Password: password, Token: token, sid: $.cookie("sid")};
    console.log(data);
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
                this.props.loginChange();
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
        });
  },
});

var UserContent = React.createClass({
  render: function(){
    return (
      <div>
        <div>
        Loggedin User Content
        </div>
        <div>
        username: {this.props.username}
        </div>
        <div>
        name: {this.props.name}
        </div>
        <div>
        admin: {this.props.isAdmin}
        </div>
        <div>
        props: {this.props}
        </div>
      </div>
    );
  },
});

var MobileWebDoorlock = React.createClass({
  componentDidMount: function() {
    this.checkLogin();
  },
  getInitialState: function(){
    return {
      loggedIn: false,
      Username: '',
      Name: '',
      IsAdmin: '',
    };
  },
  login: function() {
    this.setState({loggedIn: true});
  },
  checkLogin: function() {
    $.ajax({
            url: API_URL + CHECK_LOGIN,
            type: "POST",
            //crossDomain: true,
            data: {sid: $.cookie("sid")},
            //data: data,
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
                //this.props.loginChange();
                console.log(result);
                if(this.state.loggedIn == false){
                  this.setState({
                    loggedIn: true,
                    Username: result.Username,
                    Name: result.Name,
                    IsAdmin: result.IsAdmin,
                  });
                }
                return true;
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
                return false;
            }.bind(this)
        });
  },
  checkLoggedIn: function() {
    return this.state.loggedIn;
    //return this.state.loggedIn && this.checkLogin();
    //console.log("user loggin: " +this.checkLogin() && this.state.loggedIn);
    //return this.checkLogin() && this.state.loggedIn;
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
          <Nav isLoggedIn={this.state.loggedIn} />
          <div className="content">
            {this.checkLoggedIn() ? <UserContent username={this.state.Username} name={this.state.Name} isAdmin={this.state.IsAdmin} /> : <LoginScreen loginChange={this.login} />}
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
