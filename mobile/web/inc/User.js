'use strict';
var common = require('./Common');

var User = React.createClass({
  getInitialState: function() {
    return {
      allUserInfo: '',
    };
  },
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.USER_INFO,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function(result) {
        console.log("userInfo:");
        console.log(result);
        this.setState({
          allUserInfo: result,
        });
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  render: function() {
    return (
      <div>
        <div className="userInfo">
          Username: {this.state.allUserInfo.Username} TODO fix this
        </div>
        <div className="userInfo">
          Name: <input ref="name" id="name" className="infotextbox" placeholder={this.state.allUserInfo.Name} />
        </div>
        <div className="userInfo">
          Email: <input ref="email" id="email" className="infotextbox" placeholder={this.state.allUserInfo.Email} />
        </div>
        <div className="userInfo">
          CardID: <input ref="cardID" id="cardID" className="infotextbox" placeholder={this.state.allUserInfo.CardID} />
        </div>
        <div className="userInfo">
          AuthyID: <input ref="authyID" id="authyID" className="infotextbox" placeholder={this.state.allUserInfo.AuthyID} />
        </div>
        <div className="userInfo">
          <button id="update" type="button" onClick={this.updateInfo}>Update Info</button>
        </div>
      </div>
    )
  }
});

module.exports = User;