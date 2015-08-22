'use strict';
var Navigation = ReactRouter.Navigation;
var common = require('./Common');

var Nav = React.createClass({
  mixins: [Navigation],
  logout: function(){
    $.ajax({
      url: common.API_URL + common.LOGOUT,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function (result) {
        console.log(result);
        if(result.success){
          window.location.href = "/";
        }
      }.bind(this),
      error: function (xhr, status, error) {
        console.log(status);
        console.log(error);
      }.bind(this)
    });
  },
  render: function () {
    var adminNav = '';
    var test;
    //TODO fix this
    var isAdmin = true;
    if (isAdmin) {
      //TODO fix this
      adminNav = `<li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('admin')}>Admin Page</a>
          </li>
          < li >
            <a href="/config/">Config Page</a>
          </li>`;
    }
    return (
      <div className="nav">
        <ul>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('home')}>Home</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('user')}>User Info</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('lock')}>Lock Status</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('admin')}>Admin Page</a>
          </li>
          < li >
            <a href="/config/">Config Page</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.logout}>Logout</a>
          </li>
        </ul>
      </div>
    );
  }
});

module.exports = Nav;