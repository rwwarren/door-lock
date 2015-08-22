'use strict';

var Home = React.createClass({
  render: function () {
    return (
      <div>
        Home {this.props.Username}
      </div>
    );
  }
});

module.exports = Home;