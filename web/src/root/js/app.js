/*
 * PiDuinoLock web app
 */
"use strict"

var PiLock = React.createClass({
  getInitialState: function() {
    return {
      isLoggedIn: true,
    };
  },
  render: function() {
        //<Nav />
    return (
      <div className="container">
        <Logo />
        {this.state.isLoggedIn ? <LoggedIn getConfigPage={this.getConfigPage}/> : <LoginPage attemptLogin={this.attemptLogin} />}
        <Footer />
      </div>
    );
  },
  checkLoggedIn: function() {
    console.log("clicked");
  },
  attemptLogin: function() {
    console.log("clicked");
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
            <a href="javascript:void(0);">Home</a>
          </li>
          <li>
            <a href="javascript:void(0);">User Info</a>
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
    return (
      <div className="">
        <Nav getConfigPage={this.props.getConfigPage} />
        Logged In Page
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
          <button id="submit" type="button" onClick={this.props.attemptLogin}>Login</button>
        </div>
      </div>
    );
  },
});

React.render(<PiLock />, document.body);

