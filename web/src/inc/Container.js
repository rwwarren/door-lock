'use strict';

var Router = ReactRouter;
var { Route, RouteHandler, Link } = Router;
var Logo = require('./Logo');
var Footer = require('./Footer');
var Nav = require('./Nav');

var Container = React.createClass({

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