'use strict';

var Router = ReactRouter;
var { Route, RouteHandler, Link } = Router;
var Logo = require('./Logo');
var Footer = require('./Footer');
var Nav = require('./Nav');
var common = require('./Common');

var Container = React.createClass({
  checkLoggedIn: function () {
    $.ajax({
      url: common.API_URL + common.CHECK_LOGIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function (result) {
        console.log(result);
        //if(this.state.loggedIn == false){
        this.setState({
          isLoggedIn: true,
          Username: result.Username,
          Name: result.Name,
          IsAdmin: result.IsAdmin,
        });
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
    if (!this.state.isLoggedIn) {
      //TODO change back
      //this.checkLoggedIn();
    }
  },
  getInitialState: function () {
    return {
      //content: ''
      //isLoggedIn: false
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

        <Nav />
        <RouteHandler />
        <Footer />
      </div>
    );
  }
});


module.exports = Container;