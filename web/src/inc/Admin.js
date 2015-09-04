"use strict";
var common = require('./Common');

var Admin = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.ADMIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function(result) {
        console.log(result);
        this.setState({
          adminData: result,
          loaded: true
        });
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
      console.log("createList called");
      console.log(userList);
      return (
        <ul>
          {
            userList.map(function(username) {
                return (
                  <li key={username} className={type}>
                    {username}
                  </li>
                );
              }
            )
          }
        </ul>
      )
    }
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