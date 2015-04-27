/*
 * PiDuinoLock web app
 */
"use strict"

var API_URL = "http://m.localhost/";
var LOGIN = "login.php";
var CHECK_LOGIN = "checklogin.php";
var LOCK_STATUS = "lockStatus.php";
var USER_INFO = "userInfo.php";
var ADMIN = "admin.php";

var PiLock = React.createClass({
  componentDidMount: function() {
    if($.cookie("sid") == null) {
      $.cookie("sid", makeid());
    }
  },
  getInitialState: function() {
    return {
      isLoggedIn: false,
      Username: '',
      Name: '',
      IsAdmin: '',
      page: 'home',
    };
  },
  render: function() {
        //<Nav />

    if(!this.state.isLoggedIn){
      this.checkLoggedIn();
    }
    var page = '';
    if (!this.state.isLoggedIn) {
      page = <LoginPage attemptLogin={this.attemptLogin} />;
    } else if (this.state.page === 'home') {
      page = <LoggedIn userInfo={this.state}/>
    } else if (this.state.page === 'user') {
      page = <UserInfo username={this.state.Username}/>;
    } else if (this.state.page === 'lock') {
      page = <LockStatus />;
    } else if (this.state.page === 'admin') {
      page = <AdminPage />
    }
    return (
      <div className="container">
        <Logo />
        {this.state.isLoggedIn ? <Nav getConfigPage={this.getConfigPage} changePage={this.changePage} logout={this.logout}/> : ''}
        {page}
        <Footer />
      </div>
    );
  },
  checkLoggedIn: function() {
    $.ajax({
            url: API_URL + CHECK_LOGIN,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
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
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
                //return false;
            }.bind(this)
        });
  },
  attemptLogin: function(data) {
    console.log(data);
    $.ajax({
            url: API_URL + LOGIN,
            type: "POST",
            data: data,
            dataType: "json",
            success:function(result){
                //this.props.loginChange();
                this.setState({
                  isLoggedIn: true,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
    });
  },
  changePage: function(pageName) {
    this.setState({page: pageName});
  },
  getConfigPage: function() {
    document.location.href = '/config/';
  },
  logout: function() {
    document.location.href = '/logout.php';
  },
});

var Logo = React.createClass({
  render: function() {
    return (
      <div className="logo">
      </div>
    );
  },
});

var Footer = React.createClass({
  render: function() {
    var now = new Date
    var theYear=now.getYear()
    if (theYear < 1900) {
      theYear=theYear+1900
    }
    return (
      <div className="footer">
        &copy; {theYear} PiLock Web Interface
      </div>
    );
  },
});

var Nav = React.createClass({
  render: function() {
    return (
      <div className="nav">
        <ul>
          <li>
            <a href="javascript:void(0);" onClick={this.homePage}>Home</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.userPage}>User Info</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.lockPage}>Lock Status</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.adminPage}>Admin Page</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.props.getConfigPage}>Config Page</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.props.logout}>Logout</a>
          </li>
        </ul>
      </div>
    );
  },
  homePage: function() {
    this.props.changePage("home");
  },
  userPage: function() {
    this.props.changePage("user");
  },
  lockPage: function() {
    this.props.changePage("lock");
  },
  adminPage: function() {
    this.props.changePage("admin");
  },
});

var UserInfo = React.createClass({
  componentDidMount: function() {
   $.ajax({
            url: API_URL + USER_INFO,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
                console.log(result);
                //this.props.loginChange();
                this.setState({
                  allUserInfo: result,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
  });
  },
  getInitialState: function() {
    return {
      allUserInfo: '',
    };
  },
  render: function() {
    return (
      <div className="">
        <div>
          User Info Page
        </div>
        <div>
          Username: {this.props.username}
        </div>
        <div>
          Name:
          <input ref="name" id="name" placeholder={this.state.allUserInfo.Name}/>
        </div>
        <div>
          Email:
          <input ref="email" id="email" placeholder={this.state.allUserInfo.Email}/>
        </div>
        <div>
          CardID:
          <input ref="cardID" id="cardID" placeholder={this.state.allUserInfo.CardID}/>
        </div>
        <div>
          AuthyID:
          <input ref="authyID" id="authyID" placeholder={this.state.allUserInfo.AuthyID}/>
        </div>
        <div>
          Current Password:
          <input type="password" />
        </div>
        <div>
          <button id="update" type="button" onClick={this.updateInfo}>Update Info</button>
        </div>
      </div>
    );
  },
  updateInfo: function() {
    console.log("updating user info");
    var name = this.refs.name.getDOMNode().value.trim();
    var email = this.refs.email.getDOMNode().value.trim();
    var cardID = this.refs.cardID.getDOMNode().value.trim();
    var authyID = this.refs.authyID.getDOMNode().value.trim();
    var data = {Name: name, Email: email, CardID: cardID, AuthyID: authyID};
    console.log(data);
  },
});

var LockStatus = React.createClass({
  componentDidMount: function() {
   $.ajax({
            url: API_URL + LOCK_STATUS,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
                console.log(result);
                this.setState({
                  isLocked: result.isLocked,
                  Status: result.Status,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
  });
  },
  getInitialState: function() {
    return {
      isLocked: '',
      Status: '',
    };
  },
  render: function() {
     var lockOrUnlock = (this.state.isLocked == "1") ? "Unlock" : "Lock";
      return (
      <div className="">
        <div className="userInfo">
          Status: {this.state.Status}
        </div>
        <div className="userInfo">
          isLocked: {this.state.isLocked}
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

var AdminPage = React.createClass({
  componentDidMount: function() {
   $.ajax({
            url: API_URL + ADMIN,
            type: "POST",
            data: {sid: $.cookie("sid")},
            dataType: "json",
            success:function(result){
                console.log(result);
                this.setState({
                  adminData: result,
                });
            }.bind(this),
            error:function(xhr,status,error){
                console.log(status);
                console.log(error);
            }
  });
  },
  getInitialState: function() {
    return {
      adminData: '',
    };
  },
  render: function() {
    return (
      <div className="">
        <div>
          Admin Page. Sorry, but this has a lot of security work to do. Needs API redesign as well
        </div>
        <div>
          {JSON.stringify(this.state.adminData)}
        </div>
      </div>
    );
  },
});

var LoggedIn = React.createClass({
  render: function() {
    return (
      <div className="">
        <div>
          Welcome {this.props.userInfo.Name}!
        </div>
        <div>
          Please click on another page for more information
        </div>
      </div>
    );
  },
});

var LoginPage = React.createClass({
  render: function() {
    return (
      <div className="login">
        <div className="inputtext">
          <input className="textbox" id="username" ref="username" placeholder="username" />
        </div>
        <div className="inputtext">
          <input className="textbox" type="password" id="password" ref="password" placeholder="password" />
        </div>
        <div className="inputtext">
          <input className="textbox" id="token" ref="token" placeholder="token" />
        </div>
        <div className="inputtext">
          <button id="submit" type="button" onClick={this.attemptLogin}>Login</button>
        </div>
      </div>
    );
  },
  attemptLogin: function() {
          //<button id="submit" type="button" onClick={this.props.attemptLogin}>Login</button>
    console.log("clicked in child");
    var username = this.refs.username.getDOMNode().value.trim();
    var password = this.refs.password.getDOMNode().value.trim();
    var token = this.refs.token.getDOMNode().value.trim();
    var data = {Username: username, Password: password, Token: token, sid: $.cookie("sid")};
    this.props.attemptLogin(data);
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

React.render(<PiLock />, document.body);

