'use strict';

var Nav = React.createClass({
  render: function() {
    return (
      <div className="navigation">
        <a className="links" href="javascript:void(0);" onClick={this.props.homeTab}>Home</a>
        |
        <a className="links" href="javascript:void(0);" onClick={this.props.userInfo}>User Info</a>
        |
        <a className="links" href="javascript:void(0);" onClick={this.props.lockInfo}>Lock Status</a>
        |
        <a className="links" href="/logout">Logout</a>
      </div>
    )
  }
});

module.exports = Nav;