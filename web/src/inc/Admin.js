"use strict";
var common = require('./Common');

var Admin = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.ADMIN,
      type: "POST",
      //data: {sid: $.cookie("sid")},
      contentType: "application/json",
      data: JSON.stringify({
        sid: $.cookie("sid")
      }),
      dataType: "json",
      success: function(result) {
        console.log(result);
        this.setState({
          adminData: result,
          loaded: true
        });
        $.getScript("/js/jqueryDoorlock.js");
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  getInitialState: function() {
    return {
      adminData: '',
      loaded: false
    };
  },
  createlist: function(userList, type) {
    if(this.state.loaded) {
      //console.log("createList called");
      //console.log(userList);
      //<ul id={type} className="connectedSortable ui-sortable">
      var currentUser = this.props.Username;
      return (
        <ul id={type} className="connectedSortable">
          {
            userList
              .filter(function(username) {
                return username.username !== currentUser;
              })
              .map(function(username) {
                return (
                  <li key={username.username} className={type} id={username.userID}>
                    {username.username}
                  </li>
                );
              }
            )
          }
        </ul>
      )
    }
  },
  addCurrentAdmin: function() {
    console.log(this.props.Username);
    return (
      <ul className="connectedSortable">
        <li className="currentUser">
          {this.props.Username}
        </li>
      </ul>
    );
  },
  render: function() {
    return (
      <div className="adminPage">
        <table className="userList">
          <tr>
            <th>
              Admins:
            </th>
            <th>
              Active Users:
            </th>
            <th>
              Inactive Users:
            </th>
          </tr>
          <tr>
            <td>
              {this.addCurrentAdmin(this.props.Username)}
              {this.createlist(this.state.adminData.Admins, "admin")}
            </td>
            <td>
              {this.createlist(this.state.adminData.ActiveUsers, "active")}
            </td>
            <td>
              {this.createlist(this.state.adminData.InactiveUsers, "inactive")}
            </td>
          </tr>
        </table>
      </div>
    );
  }
});

module.exports = Admin;