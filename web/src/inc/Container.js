'use strict';

var Router = ReactRouter;
var { Route, RouteHandler, Link } = Router;
var Navigation = ReactRouter.Navigation;
var Logo = require('./Logo');
var LoginPage = require('./LoginPage');
var Footer = require('./Footer');
var Nav = require('./Nav');
var common = require('./Common');

var Container = React.createClass({
  mixins: [Navigation],
  checkLoggedIn: function() {
    $.ajax({
      url: common.API_URL + common.CHECK_LOGIN,
      type: "POST",
      //data: {sid: $.cookie("sid")},
      data: JSON.stringify({
        sid: $.cookie("sid")
      }),
      dataType: "json",
      contentType: "application/json",
      success: function(result) {
        console.log(result);
        //TODO fix null pointer below
        if(result.success == 1) {
          this.setState({
            isLoggedIn: true,
            Username: result.username,
            Name: result.name,
            IsAdmin: result.admin
            //Username: result.Username,
            //Name: result.Name,
            //IsAdmin: result.IsAdmin
          });
        } else {
          this.replaceWith('/');
        }
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }.bind(this)
    });
  },
  componentDidMount: function() {
    if($.cookie("sid") == null) {
      $.cookie("sid", common.makeid());
    }
    this.checkLoggedIn();
  },
  getInitialState: function() {
    return {
      isLoggedIn: false
    };
  },
  render: function() {
    return (
      <div className="container">
        <Logo />
        {this.state.isLoggedIn ? <Nav /> : ''}
        {this.state.isLoggedIn ?
          <RouteHandler Username={this.state.Username} Password={this.state.Password} IsAdmin={this.state.IsAdmin}/> :
          <LoginPage /> }
        <Footer />
      </div>
    );
  }
});

module.exports = Container;