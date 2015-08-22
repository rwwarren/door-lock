'use strict';
var common = require('./Common');

var Home = React.createClass({
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
        console.log(result);
        //this.props.loginChange();
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
        <div>
          Welcome, {this.state.allUserInfo.Name}!
        </div>
        <div>
          This is the lock user page
        </div>
      </div>
    );
  },
});


module.exports = Home;