'use strict';

var Router = ReactRouter;
var { Route, RouteHandler, Link } = Router;
var common = require('./Common');
var Footer = require('./Footer');
var Header = require('./Header');
var Nav = require('./Nav');

var Container = React.createClass({
  componentDidMount: function() {
    if($.cookie("sid") == null) {
      $.cookie("sid", common.makeid());
    }
    this.checkLogin();
  },
  getInitialState: function() {
    return {
      loggedIn: false,
      //Username: '',
      //Name: '',
      //IsAdmin: '',
      //selectedTab: 'Home',
      //allUserInfo: '',
      //lockStatus: '',
    };
  },
  //changeTab: function() {
  //  if(this.state.selectedTab === "Home") {
  //    return <UserContent username={this.state.Username} name={this.state.Name} isAdmin={this.state.IsAdmin}/>;
  //  } else if(this.state.selectedTab === "UserInfo") {
  //    return <UserInfo info={this.state.allUserInfo} username={this.state.Username}/>;
  //  } else if(this.state.selectedTab === "LockStatus") {
  //    return <LockStatus info={this.state.lockStatus}/>;
  //  } else {
  //    return "Error";
  //  }
  //},
  login: function(result) {
    //TODO fix this so that the user's name shows up without refresh
    this.setState({
      loggedIn: true,
      Username: result.Username,
      Name: result.Name,
      IsAdmin: result.IsAdmin
    });
  },
  checkLogin: function() {
    $.ajax({
      url: common.API_URL + common.CHECK_LOGIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      //dataType: "jsonp",
      dataType: "json",
      success: function(result) {
        console.log(result);
        if(this.state.loggedIn == false) {
          this.setState({
            loggedIn: true,
            Username: result.Username,
            Name: result.Name,
            IsAdmin: result.IsAdmin,
          });
        }
        return true;
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
        return false;
      }.bind(this)
    });
  },
  checkLoggedIn: function() {
    return this.state.loggedIn;
  },
  render: function() {
    var now = new Date
    var theYear = now.getYear()
    if(theYear < 1900) {
      theYear = theYear + 1900
    }
    //{this.checkLoggedIn() ? <UserContent username={this.state.Username} name={this.state.Name} isAdmin={this.state.IsAdmin} /> : <LoginScreen loginChange={this.login} />}
    //<Nav isLoggedIn={this.state.loggedIn} userInfo={this.userInfo} lockInfo={this.lockInfo} homeTab={this.homePage} />
    //<div className="content">
    //  {this.checkLoggedIn() ? this.changeTab() : <LoginScreen loginChange={this.login} />}
    //</div>
    return (
      <div className="container">
        <Header />
        <div className="body">
          <Nav />
          <RouteHandler />
        </div>
        <Footer />
      </div>
    );
  },
  userInfo: function() {
    $.ajax({
      url: common.API_URL + common.USER_INFO,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function(result) {
        console.log(result);
        //this.props.loginChange();
        this.setState({
          selectedTab: "UserInfo",
          allUserInfo: result,
        });
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  lockInfo: function() {
    $.ajax({
      url: common.API_URL + common.LOCK_STATUS,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function(result) {
        //this.props.loginChange();
        console.log(result);
        this.setState({
          selectedTab: "LockStatus",
          lockStatus: result,
        });
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  homePage: function() {
    this.setState({selectedTab: "Home"});
  },
});

module.exports = Container;