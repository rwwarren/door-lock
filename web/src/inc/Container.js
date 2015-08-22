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
  checkLoggedIn: function () {
    $.ajax({
      url: common.API_URL + common.CHECK_LOGIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function (result) {
        console.log(result);
        //if(this.state.loggedIn == false){
        if(result.success == 1){
          this.setState({
            isLoggedIn: true,
            Username: result.Username,
            Name: result.Name,
            IsAdmin: result.IsAdmin
          });
        } else {
          this.replaceWith('/');
        }
        //}
        //return true;
      }.bind(this),
      error: function (xhr, status, error) {
        console.log(status);
        console.log(error);
        //return false;
      }.bind(this)
    });
  },
  componentDidMount: function () {
    if ($.cookie("sid") == null) {
      $.cookie("sid", common.makeid());
    }
    this.checkLoggedIn();
    //if (!this.state.isLoggedIn) {
    //  this.replaceWith('/');
    //}
  },
  getInitialState: function () {
    return {
      //content: ''
      isLoggedIn: false
    };
  },
  render: function () {
    //{content}
    //  {this.state.isLoggedIn ? <Nav getConfigPage={this.getConfigPage} changePage={this.changePage} logout={this.logout}/> : ''}
    //  {page}
    //    {this.state.isLoggedIn ? <Navigation /> : ''}
    return (
      <div className="container">
        <Logo />
        {this.state.isLoggedIn ? <Nav /> : ''}
        {this.state.isLoggedIn ? <RouteHandler Username={this.state.Username} Password={this.state.Password} IsAdmin={this.state.IsAdmin} /> : <LoginPage /> }
        <Footer />
      </div>
    );
  }
});


module.exports = Container;