"use strict";
var common = require('./Common');

var Admin = React.createClass({
  componentDidMount: function () {
    $.ajax({
      url: common.API_URL + common.ADMIN,
      type: "POST",
      data: {sid: $.cookie("sid")},
      //headers: common.HEADERS,
      dataType: "json",
      success: function (result) {
        console.log(result);
        this.setState({
          adminData: result,
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
      adminData: '',
    };
  },
  render: function () {
    return (
      <div className="">
        <div>
          Admin Page. Sorry, but this has a lot of security work to do. Needs API redesign as well
        </div>
        <div>
          {JSON.stringify(this.state.adminData)}
        </div>
      </div>
    );
  },
});

module.exports = Admin;