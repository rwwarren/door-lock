'use strict';
var Admin = require('./Admin');
var Configuration = require('./Config');
var Container = require('./Container');
var Home = require('./Home');
var FourOFour = require('./FourOFour');
var Lock = require('./Lock');
var User = require('./User');

//var isMobile = require('./isMobile');
//if (isMobile()) {
//}

var DefaultRoute = ReactRouter.DefaultRoute;
var HistoryLocation = ReactRouter.HistoryLocation;
var NotFoundRoute = ReactRouter.NotFoundRoute;
var Route = ReactRouter.Route;

//var routes = (
//  //{/* config */}
//  //TODO add this back in
//  //<Route path="/config/" name="config" handler={Configuration}/>
//  <Route path="/" handler={Container}>
//    {/* home */}
//    <DefaultRoute name="home" handler={Home}/>
//    {/* admin */}
//    <Route path="admin/" name="admin" handler={Admin}/>
//    {/* users */}
//    <Route path="user/" name="user" handler={User}/>
//    {/* lock */}
//    <Route path="lock/" name="lock" handler={Lock}/>
//    {/* 404 */}
//    <NotFoundRoute handler={FourOFour}/>
//  </Route>
//);

var routes = (
  //{/* config */}
  //TODO add this back in
  <Route path="">
    <Route path="/" handler={Container}>
      {/* home */}
      <DefaultRoute name="home" handler={Home}/>
      {/* admin */}
      <Route path="admin/" name="admin" handler={Admin}/>
      {/* users */}
      <Route path="user/" name="user" handler={User}/>
      {/* lock */}
      <Route path="lock/" name="lock" handler={Lock}/>
    </Route>
    <Route path="/config/" name="config" handler={Configuration}/>
    {/* 404 */}
    <NotFoundRoute handler={FourOFour}/>
  </Route>
);

ReactRouter.run(routes, HistoryLocation, function(Handler) {
  React.render(<Handler />, document.body);
});