'use strict';
var common = require('./Common');
var Navigation = ReactRouter.Navigation;

var LoginPage = React.createClass({
  mixins: [Navigation],
  login: function() {
    console.log("login attempt");
    var username = this.refs.username.getDOMNode().value.trim();
    var password = this.refs.password.getDOMNode().value.trim();
    var token = this.refs.token.getDOMNode().value.trim();
    if(!username || !password) {
      console.log("something null");
      $('#passwordCheck').css({
        'color': '#000000',
        'display': 'block'
      }).html('Error, No username or password entered!');
      return;
    }
    var data = {username: username, password: password, token: token, sid: $.cookie("sid")};
    console.log(data);
    $.ajax({
      url: common.API_URL + common.LOGIN,
      type: "POST",
      data: JSON.stringify(data),
      dataType: "json",
      contentType: "application/json",
      success: function(result) {
        console.log("results");
        console.log(result);
        if(result.status.success) {
          window.location.href = "/";
        }
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
        console.log("error! such login!");
        $('#passwordCheck').css({
          'color': '#000000',
          'display': 'block'
        }).html('Error, username or password incorrect!');
      }.bind(this)
    });
  },
  render: function() {
    return (
      <div className="loginpage">
        <div className="loginform">
          <div className="logintitle">
            User Login
          </div>
          <input className="inputinput" ref="username" id="username" placeholder="username"/>
          <input className="inputinput" type="password" ref="password" id="password" placeholder="password"/>
          <input className="inputinput" ref="token" id="token" placeholder="token"/>
          <button className="loginbutton" id="update" type="button" onClick={this.login}>Login</button>
          <div id="passwordCheck"></div>
          <div className="forgotlink">
            <a href="/forgotpassword">Forgot Password</a>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = LoginPage;