/*
 * PiDuinoLock web app
 */
"use strict"

var API_URL = "http://m.localhost/";
var LOGIN = "login.php";
var CHECK_LOGIN = "checklogin.php";
var LOCK_STATUS = "lockStatus.php";
var USER_INFO = "userInfo.php";

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
      allUserInfo: '',
      lockStatus: '',
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
      page = <LoggedIn  userInfo={this.state}/>
    } else if (this.state.page === 'user') {
    } else if (this.state.page === 'lock') {
    } else if (this.state.page === 'admin') {
    }
    return (
      <div className="container">
        <Logo />
        {this.state.isLoggedIn ? <Nav getConfigPage={this.getConfigPage} changePage={this.changePage} /> : ''}
        {page}
        <Footer />
      </div>
    );
  },
  checkLoggedIn: function() {
    console.log("clicked");
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
    console.log("clicked");
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
  getUserInfo: function() {
    console.log("clicked");
  },
  getLockStatus: function() {
    console.log("clicked");
  },
  getAdminPage: function() {
    console.log("clicked");
  },
  getConfigPage: function() {
    document.location.href = '/config/';
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
            <a href="javascript:void(0);">Lock Status</a>
          </li>
          <li>
            <a href="javascript:void(0);">Admin Page</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.props.getConfigPage}>Config Page</a>
          </li>
          <li>
            <a href="javascript:void(0);">Logout</a>
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
});

var UserInfo = React.createClass({
  render: function() {
    return (
      <div className="">
        User Info
      </div>
    );
  },
});

var LockStatus = React.createClass({
  render: function() {
    return (
      <div className="">
        Lock Status
      </div>
    );
  },
});

var AdminPage = React.createClass({
  render: function() {
    return (
      <div className="">
        Admin Page
      </div>
    );
  },
});

var ConfigPage = React.createClass({
  render: function() {
    return (
      <div className="">
        Config Page
      </div>
    );
  },
});

var LoggedIn = React.createClass({
  render: function() {
          //<Nav getConfigPage={this.props.getConfigPage} />
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

