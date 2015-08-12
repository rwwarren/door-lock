'use strict';
var common = require('./Common');


var Lock = React.createClass({
  getInitialState: function() {
    return {
      lockStatus: '',
    };
  },
  componentDidMount: function() {
    $.ajax({
      url: common.API_URL + common.LOCK_STATUS,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function(result) {
        //this.props.loginChange();
        console.log(result);
        this.setState({
          lockStatus: result,
        });
      }.bind(this),
      error: function(xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  render: function() {
    var lockOrUnlock = (this.state.lockStatus.isLocked == "1") ? "Unlock" : "Lock";
    return (
      <div>
        <div className="userInfo">
          Status: {this.state.lockStatus.Status}
        </div>
        <div className="userInfo">
          isLocked: {this.state.lockStatus.isLocked}
        </div>
        <div className="userInfo">
          <button id="update" type="button" onClick={this.changeLock}>{lockOrUnlock}</button>
        </div>
      </div>
    );
  },
  changeLock: function() {
    console.log("Updating lock status");
  },
});

module.exports = Lock;