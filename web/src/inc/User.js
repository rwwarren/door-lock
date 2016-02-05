'use strict';
var common = require('./Common');
var Navigation = ReactRouter.Navigation;
var Nav = require('./Nav');

var User = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.USER_INFO,
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        sid: $.cookie("sid")
      }),
      dataType: "json",
      success: function(result) {
        console.log(result);
        this.setState({
          allUserInfo: result
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
      allUserInfo: ''
    };
  },
  render: function() {
    return (
      <div className="">
        <div>
          User Info Page
        </div>
        <div>
          Username: {this.props.Username}
        </div>
        <div>
          Name:
          <input ref="name" id="name" placeholder={this.state.allUserInfo.name}/>
        </div>
        <div>
          Email:
          <input ref="email" id="email" placeholder={this.state.allUserInfo.email}/>
        </div>
        <div>
          CardID:
          <input ref="cardID" id="cardID" placeholder={this.state.allUserInfo.cardID}/>
        </div>
        <div>
          AuthyID:
          <input ref="authyID" id="authyID" placeholder={this.state.allUserInfo.authyID}/>
        </div>
        <div>
          New Password:
          <input id="password" type="newPassword"/>
        </div>
        <div>
          Confirm New Password:
          <input id="password" type="confirmNewPassword"/>
        </div>
        <div>
          Current Password:
          <input id="password" type="password" onChange={this.changePassword}/>
        </div>
        <div>
          <button id="update" type="button" onClick={this.updateInfo} disabled>Update Info</button>
        </div>
      </div>
    );
  },
  changePassword: function() {
    if($.trim($('#password').val()).length != 0){
      $('#update').prop('disabled', false);
    } else {
      $('#update').prop('disabled', true);
    }
  },
  updateInfo: function() {
    console.log("updating user info");
    var name = this.refs.name.getDOMNode().value.trim();
    var email = this.refs.email.getDOMNode().value.trim();
    var cardID = this.refs.cardID.getDOMNode().value.trim();
    var authyID = this.refs.authyID.getDOMNode().value.trim();
    var data = {Name: name, Email: email, CardID: cardID, AuthyID: authyID};
    console.log(data);
  }
});

module.exports = User;
