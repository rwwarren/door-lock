'use strict';
//var Admin = require('./Admin');
//var Configuration = require('./Config');
var Container = require('./Container');
var Home = require('./Home');
var FourOFour = require('./FourOFour');
//var Lock = require('./Lock');
//var Users = require('./Users');

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

ReactRouter.run(routes, HistoryLocation, function(Handler) {
  React.render(<Handler />, document.body);
});


var routes = (
  //<Route path="/admin/" name="admin" handler={Admin}/>
//{/* users */}
//<Route path="/users/" name="users" handler={Users}/>
//{/* lock */}
//<Route path="/lock/" name="lock" handler={Lock}/>
  <Route path="">
    <Route path="/" handler={Container}>
      {/* home */}
      <DefaultRoute name="home" handler={Home}/>
      {/* admin */}
    </Route>
    {/* 404 */}
    <NotFoundRoute handler={FourOFour}/>

  </Route>
);
