/**
 * PiDuinoLock mobile web app
 */
"use strict";

var API_URL = "http://m.localhost/";
//var API_URL = "http://api.localhost/";
var LOGIN = "login.php";
var CHECK_LOGIN = "checklogin.php";
var LOCK_STATUS = "lockStatus.php";
var USER_INFO = "userInfo.php";
//var sid = 'adf';

var UserInfo = React.createClass({
        //<div>
        //  Welcome, {this.props.info}!
        //</div>
    render: function(){
      return (
      <div>
        <div className="userInfo">
          Username: {this.props.username}
        </div>
        <div className="userInfo">
          Name: <input ref="name" id="name" className="infotextbox" placeholder={this.props.info.Name} />
        </div>
        <div className="userInfo">
          Email: <input ref="email" id="email" className="infotextbox" placeholder={this.props.info.Email} />
        </div>
        <div className="userInfo">
          CardID: <input ref="cardID" id="cardID" className="infotextbox" placeholder={this.props.info.CardID} />
        </div>
        <div className="userInfo">
          AuthyID: <input ref="authyID" id="authyID" className="infotextbox" placeholder={this.props.info.AuthyID} />
        </div>
        <div className="userInfo">
          <button id="update" type="button" onClick={this.updateInfo}>Update Info</button>
        </div>
      </div>
      );
    },
    updateInfo: function() {
      var name = this.refs.name.getDOMNode().value.trim();
      var email = this.refs.email.getDOMNode().value.trim();
      var cardID = this.refs.cardID.getDOMNode().value.trim();
      var authyID = this.refs.authyID.getDOMNode().value.trim();
      var data = {Name: name, Email: email, CardID: cardID, AuthyID: authyID};
      console.log("update info clicked");
      console.log(data);
    },
});

var LockStatus = React.createClass({
    render: function(){
      var lockOrUnlock = (this.props.info.isLocked == "1") ? "Unlock" : "Lock";
      return (
      <div>
        <div className="userInfo">
          Status: {this.props.info.Status}
        </div>
        <div className="userInfo">
          isLocked: {this.props.info.isLocked}
        </div>
        <div className="userInfo">
          <button id="update" type="button" onClick={this.changeLock}>{lockOrUnlock}</button>
        </div>
      </div>
      );
    },
    changeLock: function() {
      console.log("Updating lock status");
    },
});

var Nav = React.createClass({
  render: function(){
    if($.cookie("sid") == null) {
      $.cookie("sid", makeid());
    }
    if(!this.props.isLoggedIn){
      return null;
    }
    //return (
    //  <div className="navigation">
    //    <a className="links" href="/">Home</a>
    //     |
    //    <a className="links" href="/users">User Info</a>
    //     |
    //    <a className="links" href="/lock">Lock Status</a>
    //     |
    //    <a className="links" href="/logout">Logout</a>
    //  </div>
    //);
        //<a className="links" href="/">Home</a>
    return (
      <div className="navigation">
        <a className="links" href="javascript:void(0);" onClick={this.props.homeTab}>Home</a>
         |
        <a className="links" href="javascript:void(0);" onClick={this.props.userInfo}>User Info</a>
         |
        <a className="links" href="javascript:void(0);" onClick={this.props.lockInfo}>Lock Status</a>
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
            data: data,
            dataType: "json",
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
          Welcome, {this.props.name}!
        </div>
        <div>
          This is the lock user page
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
      selectedTab: 'Home',
      allUserInfo: '',
      lockStatus: '',
    };
  },
  changeTab: function(){
    if(this.state.selectedTab === "Home"){
      return <UserContent username={this.state.Username} name={this.state.Name} isAdmin={this.state.IsAdmin} />;
    } else if(this.state.selectedTab === "UserInfo"){
      return <UserInfo info={this.state.allUserInfo} username={this.state.Username} />;
    } else if(this.state.selectedTab === "LockStatus"){
      return <LockStatus info={this.state.lockStatus} />;
    } else {
      return "Error";
    }
  },
  login: function() {
    this.setState({loggedIn: true});
  },
  checkLogin: function() {
    $.ajax({
            url: API_URL + CHECK_LOGIN,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
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
  },
  render: function() {
      var now = new Date
      var theYear=now.getYear()
      if (theYear < 1900) {
        theYear=theYear+1900
      }
            //{this.checkLoggedIn() ? <UserContent username={this.state.Username} name={this.state.Name} isAdmin={this.state.IsAdmin} /> : <LoginScreen loginChange={this.login} />}
    return (
      <div className="container">
        <div className="header">
        </div>
        <div className="body">
          <Nav isLoggedIn={this.state.loggedIn} userInfo={this.userInfo} lockInfo={this.lockInfo} homeTab={this.homePage} />
          <div className="content">
            {this.checkLoggedIn() ? this.changeTab() : <LoginScreen loginChange={this.login} />}
          </div>
        </div>
        <div className="footer">
          &copy; {theYear} PiDuinoLock Mobile Web Interface
        </div>
      </div>
    );
  },
  userInfo: function() {
    $.ajax({
            url: API_URL + USER_INFO,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
                console.log(result);
                //this.props.loginChange();
                this.setState({
                  selectedTab: "UserInfo",
                  allUserInfo: result,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
        });
  },
  lockInfo: function() {
    $.ajax({
            url: API_URL + LOCK_STATUS,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
                //this.props.loginChange();
                console.log(result);
                this.setState({
                  selectedTab: "LockStatus",
                  lockStatus: result,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
        });
  },
  homePage: function() {
    this.setState({selectedTab: "Home"});
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
