"use strict";
var common = require('./Common');

var Admin = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.ADMIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      //headers: common.HEADERS,
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
  createlist: function(userList) {
    if(this.state.loaded) {
      console.log("createList called");
      console.log(userList);
      return (
        <ul>
          <li>
            {
              userList.map(function(username) {
                  return (
                    <li key={username}>
                      {username}
                    </li>
                  );
                }
              )
            }
          </li>
        </ul>
      )
    }
  },
  render: function() {
    //<div>
    //  {JSON.stringify(this.state.adminData)}
    //</div>
    //<div>
    //  Admin Page. Sorry, but this has a lot of security work to do. Needs API redesign as well
    //</div>
    //return (
    //  <div className="adminPage">
    //    <div className="userList" id="admins">
    //      Admins: {this.state.adminData.Admins}
    //    </div>
    //    <div className="userList" id="actives">
    //      Active: {this.state.adminData.ActiveUsers}
    //    </div>
    //    <div className="userList" id="inactives">
    //      Inactive: {this.state.adminData.InactiveUsers}
    //    </div>
    //  </div>
    //);
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
              {this.createlist(this.state.adminData.Admins)}
            </td>
            <td>
              {this.createlist(this.state.adminData.ActiveUsers)}
            </td>
            <td>
              {this.createlist(this.state.adminData.InactiveUsers)}
            </td>
          </tr>
        </table>
      </div>
    );
  }
});

module.exports = Admin;