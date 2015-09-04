'use strict';

var Footer = React.createClass({
  render: function() {
    var now = new Date;
    var theYear = now.getYear();
    if(theYear < 1900) {
      theYear = theYear + 1900
    }
    return (
      <div className="footer">
        {'\u00A9'} {theYear} PiLock Web Interface
      </div>
    );
  }
});

module.exports = Footer;