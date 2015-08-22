'use strict';
var common = require('./Common');

var Lock = React.createClass({
  componentDidMount: function () {
    $.ajax({
      url: common.API_URL + common.LOCK_STATUS,
      type: "POST",
      data: {sid: $.cookie("sid")},
      dataType: "json",
      success: function (result) {
        console.log(result);
        this.setState({
          isLocked: result.isLocked,
          Status: result.Status,
        });
      }.bind(this),
      error: function (xhr, status, error) {
        console.log(status);
        console.log(error);
      }
    });
  },
  getInitialState: function () {
    return {
      isLocked: '',
      Status: '',
    };
  },
  render: function () {
    var lockOrUnlock = (this.state.isLocked == "1") ? "Unlock" : "Lock";
    return (
      <div className="">
        <div className="userInfo">
          Status: {this.state.Status}
        </div>
        <div className="userInfo">
          isLocked: {this.state.isLocked}
        </div>
        <div className="userInfo">
          <button id="update" type="button" onClick={this.changeLock}>{lockOrUnlock}</button>
        </div>
      </div>
    );
  },
  changeLock: function () {
    console.log("Updating lock status");
  },
});

module.exports = Lock;