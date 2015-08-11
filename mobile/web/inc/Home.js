'use strict';

var Home = React.createClass({
  render: function(){
    return (
      <div>
        <div>
          Welcome, {this.props.name}!
        </div>
        <div>
          This is the lock user page
        </div>
      </div>
    );
  },
});


module.exports = Home;