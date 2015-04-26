/*
 * PiDuinoLock web app
 */
"use strict"

var PiLock = React.createClass({
  render: function() {
    return (
      <div className="container">
        <Logo />
        <Nav />
        test
        <Footer />
      </div>
    );
  },
});

var Logo = React.createClass({
  render: function() {
    return (
      <div className="logo">
      </div>
    );
  },
});

var Footer = React.createClass({
  render: function() {
    var now = new Date
    var theYear=now.getYear()
    if (theYear < 1900) {
      theYear=theYear+1900
    }
    return (
      <div className="footer">
        &copy; {theYear} PiLock Web Interface
      </div>
    );
  },
});

var Nav = React.createClass({
  render: function() {
    return (
      <div className="nav">
        my Nav
      </div>
    );
  },
});

React.render(<PiLock />, document.body);

