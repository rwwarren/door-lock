'use strict';
var Navigation = ReactRouter.Navigation;

var Nav = React.createClass({
  mixins: [Navigation],

  render: function() {
    return (
      <div className="navigation">
        <a className="links" href="javascript:void(0);" onClick={() => this.transitionTo('home')}>Home</a>
        |
        <a className="links" href="javascript:void(0);" onClick={() => this.transitionTo('user')}>User Info</a>
        |
        <a className="links" href="javascript:void(0);" onClick={() => this.transitionTo('lock')}>Lock Status</a>
        |
        <a className="links" href="/logout">Logout</a>
      </div>
    )
  }
});

module.exports = Nav;