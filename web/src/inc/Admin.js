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
  validateEmail: function(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  },
  validateAuthyID: function(authyID) {
    var reg = /^[0-9]{1,10}$/;
    return reg.test(authyID);
  },
  registerUser: function(){
    //TODO check username is unique
    //email password reset?
    //fix 0 to null authyId
    //check on frontend
    var username = this.refs.username.getDOMNode().value.trim();
    var password = this.refs.password.getDOMNode().value.trim();
    var email = this.refs.email.getDOMNode().value.trim();
    var name = this.refs.name.getDOMNode().value.trim();
    var cardID = this.refs.cardID.getDOMNode().value.trim();
    var authyID = this.refs.authyID.getDOMNode().value.trim();
    var isAdmin = this.refs.isAdmin.getDOMNode().checked;
    var isValidEmail = this.validateEmail(email);
    var truth = true;
    if(username && password && name && isValidEmail && (!authyID || this.validateAuthyID(authyID))){
      $.ajax({
        url: common.API_URL + common.REGISTER_USER,
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
          sessionRequest: {
            sid: $.cookie("sid")
          },
          name: name,
          username: username,
          password: password,
          email: email,
          cardID: cardID,
          authyID: parseInt(authyID),
          isAdmin: isAdmin
        }),
        dataType: "json",
        success: function(result) {
          console.log(result);
        }.bind(this),
        error: function(xhr, status, error) {
          console.log(status);
          console.log(error);
        }
      });
    }
  },
  addUserText: function(){
    if(this.state.isAddingUser){
      this.showAddUser();
    } else {
      this.hideAddUser();
    }
  },
  showAddUser: function(){
    $(".addUser").show();
    $("#addUserButton").text("Cancel Add User");
    console.log("test 1234");
    this.setState({
      isAddingUser: false
    });
  },
  hideAddUser: function(){
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
          <div>
          <input type="text" name="username" ref="username" placeholder="username"></input>
          </div>
          <div>
          <input type="password" name="password" ref="password" placeholder="password"></input>
          </div>
          <div>
          <input type="text" name="name" ref="name" ref="name" placeholder="name"></input>
          </div>
          <div>
          <input type="text" name="email" ref="email" placeholder="email"></input>
          </div>
          <div>
          <input type="text" name="cardID" ref="cardID" placeholder="cardID"></input>
          </div>
          <div>
          <input type="text" name="authyID" ref="authyID" placeholder="authyID"></input>
          </div>
          <div>
          Admin? <input type="checkbox" ref="isAdmin" name="isAdmin"></input>
          </div>
          <div>
          <input type="submit" value="Submit" onClick={this.registerUser}/>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Admin;
