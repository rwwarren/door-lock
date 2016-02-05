"use strict";
var common = require('./Common');

var Admin = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.ADMIN,
      type: "POST",
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
      loaded: false,
      isAddingUser: true
    };
  },
  createlist: function(userList, type) {
    if(this.state.loaded) {
      var currentUser = this.props.Username;
      return (
        <ul id={type} className="connectedSortable">
          {
            userList
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
      <ul>
        <li className="currentUser">
          {this.props.Username}
        </li>
      </ul>
    );
  },
  addUserText: function(){
    if(this.state.isAddingUser){
      this.showAddUser();
    } else {
      this.hideAddUser();
    }
  },
  showAddUser: function(){
    // document.getElementById('addUser').style.display = "block";
    $(".addUser").show();
    $("#addUserButton").text("Cancel Add User");
    console.log("test 1234");
    this.setState({
      isAddingUser: false
    });
  },
  hideAddUser: function(){
    // document.getElementById('addUser').style.display = "block";
    $("#addUserButton").text("Add User");
    $(".addUser").hide();
    this.setState({
      isAddingUser: true
    });
    console.log("test 1234");
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
        <button onClick={this.addUserText} id="addUserButton">
          Add User
        </button>
        <div className="addUser">
          Create new user:
          <input type="text" name="username" placeholder="username"></input>
          <input type="password" name="password" placeholder="password"></input>
          <input type="text" name="email" placeholder="email"></input>
          <input type="text" name="cardID" placeholder="cardID"></input>
          <input type="text" name="authyID" placeholder="authyID"></input>
          <input type="checkbox" name="isAdmin"></input>
          <input type="submit" value="Submit"/>
        </div>
      </div>
    );
  }
});

module.exports = Admin;
