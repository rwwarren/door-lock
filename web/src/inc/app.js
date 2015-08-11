/*
 * @jsx React.DOM
 * PiDuinoLock web app
 */

var PiLock = React.createClass({
  componentDidMount: function () {
    if ($.cookie("sid") == null) {
      $.cookie("sid", makeid());
    }
  },
  getInitialState: function () {
    return {
      isLoggedIn: false,
      Username: '',
      Name: '',
      IsAdmin: '',
      page: 'home',
    };
  },
  render: function () {

    if (!this.state.isLoggedIn) {
      this.checkLoggedIn();
    }
    var page = '';
    if (!this.state.isLoggedIn) {
      page = <LoginPage attemptLogin={this.attemptLogin}/>;
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
        {this.state.isLoggedIn ?
          <Nav getConfigPage={this.getConfigPage} changePage={this.changePage} logout={this.logout}/> : ''}
        {page}
        <Footer />
      </div>
    );
  },
  checkLoggedIn: function () {
    $.ajax({
      url: API_URL + CHECK_LOGIN,
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
  attemptLogin: function (data) {
    console.log(data);
    $.ajax({
      url: API_URL + LOGIN,
      type: "POST",
      data: data,
      dataType: "json",
      success: function (result) {
        //this.props.loginChange();
        this.setState({
          isLoggedIn: true,
        });
      }.bind(this),
      error: function (xhr, status, error) {
        console.log(xhr);
        console.log(status);
        console.log(error);
      }
    });
  },
  changePage: function (pageName) {
    this.setState({page: pageName});
  },
  getConfigPage: function () {
    document.location.href = '/config/';
  },
  logout: function () {
    document.location.href = '/logout.php';
  },
});


var LoggedIn = React.createClass({
  render: function () {
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
  render: function () {
    return (
      <div className="login">
        <div className="inputtext">
          <input className="textbox" id="username" ref="username" placeholder="username"/>
        </div>
        <div className="inputtext">
          <input className="textbox" type="password" id="password" ref="password" placeholder="password"/>
        </div>
        <div className="inputtext">
          <input className="textbox" id="token" ref="token" placeholder="token"/>
        </div>
        <div className="inputtext">
          <button id="submit" type="button" onClick={this.attemptLogin}>Login</button>
        </div>
      </div>
    );
  },
  attemptLogin: function () {
    //<button id="submit" type="button" onClick={this.props.attemptLogin}>Login</button>
    console.log("clicked in child");
    var username = this.refs.username.getDOMNode().value.trim();
    var password = this.refs.password.getDOMNode().value.trim();
    var token = this.refs.token.getDOMNode().value.trim();
    var data = {Username: username, Password: password, Token: token, sid: $.cookie("sid")};
    this.props.attemptLogin(data);
  },
});

React.render(<PiLock />, document.body);

module.exports = PiLock;
