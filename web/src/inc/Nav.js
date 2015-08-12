'use strict';
var Navigation = ReactRouter.Navigation;

var Nav = React.createClass({
  mixins: [Navigation],

  render: function () {
    //<a href="javascript:void(0);" onClick={() => this.transitionTo('config')}>Config Page</a>
    var adminNav = '';
    var test;
    //TODO fix this
    var isAdmin = true;
    if (isAdmin) {
      //TODO fix this
      adminNav = `<li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('admin')}>Admin Page</a>
          </li>
          < li >
            <a href="/config/">Config Page</a>
          </li>`;
      //test ={{__html: adminNav}}
    //var dangerouslySetInnerHTML={{__html: thisIsMyCopy}};
    //      dangerouslySetInnerHTML={{__html: adminNav}}
    //      <li dangerouslySetInnerHTML={{__html: adminNav}} />
    }
    return (
      <div className="nav">
        <ul>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('home')}>Home</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('user')}>User Info</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('lock')}>Lock Status</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={() => this.transitionTo('admin')}>Admin Page</a>
          </li>
          < li >
            <a href="/config/">Config Page</a>
          </li>
          <li>
            <a href="javascript:void(0);" onClick={this.props.logout}>Logout</a>
          </li>
        </ul>
      </div>
    );
  },
});

module.exports = Nav;