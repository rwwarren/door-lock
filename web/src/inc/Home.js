'use strict';

var Home = React.createClass({
  render: function () {
    return (
      <div>
        Welcome to the home page {this.props.Username}!
      </div>
    );
  }
});

module.exports = Home;