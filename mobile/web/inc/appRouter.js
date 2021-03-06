'use strict';
var Container = require('./Container');
var Home = require('./Home');
var FourOFour = require('./FourOFour');
var Lock = require('./Lock');
var User = require('./User');

//var isMobile = require('./isMobile');
//if (isMobile()) {
//    React.initializeTouchEvents(true);
//}

// DO NOT REMOVE
//require('./ErrorHandling').init();
//require('./FBSDK').init();
//require('./URLFix').fix();

var DefaultRoute = ReactRouter.DefaultRoute;
var HistoryLocation = ReactRouter.HistoryLocation;
var NotFoundRoute = ReactRouter.NotFoundRoute;
var Route = ReactRouter.Route;

var routes = (
  <Route path="">
    <Route path="/" handler={Container}>
      {/* home */}
      <DefaultRoute name="home" handler={Home}/>
      {/* admin */}
      <Route path="user/" name="user" handler={User}/>
      {/* lock */}
      <Route path="lock/" name="lock" handler={Lock}/>
    </Route>
    {/* 404 */}
    <NotFoundRoute handler={FourOFour}/>

  </Route>
);

ReactRouter.run(routes, HistoryLocation, function(Handler) {
  React.render(<Handler />, document.body);
});

