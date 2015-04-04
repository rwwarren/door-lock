/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 */
'use strict';

var React = require('react-native');
var {
  AppRegistry,
  StyleSheet,
  Text,
  View,
  ListView,
  NavigatorIOS,
  Navigator,
  ScrollView,
  NavButton,
} = React;

var HomePage = require('./HomePage');

var doorlockapp = React.createClass({
  getInitialState: function() {
      return {
        isLoggedIn: false,
      }
  },
  render: function() {
    if (!this.state.isLoggedIn) {
      return this.returnLoginPage();
      //return (
      //  <View style={styles.container}>
      //    <Text>asdf her</Text>
      //  </View>
      //);
    }
      return (
        <View style={styles.container}>
          <Text>Please Login</Text>
        </View>
      );
    //}
//      <NavigatorIOS
//        style={styles.container}
//        initialRoute={{
//          title: 'Home Page',
//          component: HomePage,
//      }}/>

//    return (
//      <Navigator
//        style={styles.container}
//        initialRoute={{name: 'My First Scene', index: 0}}
//        renderScene={(route, navigator) =>
//          <HomePage
//            name={route.name}
//            onForward={() => {
//              var nextIndex = route.index + 1;
//              navigator.push({
//                name: 'Scene ' + nextIndex,
//                index: nextIndex,
//              });
//            }}
//            onBack={() => {
//              if (route.index > 0) {
//                navigator.pop();
//              }
//            }}
//          />
//        }
//      />
//    );

//    return (
//      <Navigator
//        style={styles.container}
//        initialRoute={this.getRandomRoute()}
//        renderScene={this.renderScenes}
//        navigationBar={
//          <Navigator.BreadcrumbNavigationBar
//            routeMapper={this.navBarRouteMapper}
//          />
//        }
//      />
//    );

//    return (
//      <View style={styles.container}>
//        <Text>asdf</Text>
//      </View>
//    );
  },
//  getRandomRoute: function() {
//    return {
//      title: '#' + Math.ceil(Math.random() * 1000),
//    };
//  },
//  renderScenes: function(route, navigator) {
//    return (
//      <ScrollView style={styles.scene}>
//        <NavButton
//          onPress={() => { navigator.push(this.getRandomRoute()) }}
//          text="Push"
//        />
//        <NavButton
//          onPress={() => { navigator.immediatelyResetRouteStack([this.getRandomRoute(), this.getRandomRoute()]) }}
//          text="Reset w/ 2 scenes"
//        />
//        <NavButton
//          onPress={() => { navigator.popToTop() }}
//          text="Pop to top"
//        />
//        <NavButton
//          onPress={() => { navigator.replace(this.getRandomRoute()) }}
//          text="Replace"
//        />
//        <NavButton
//          onPress={() => { this.props.navigator.pop(); }}
//          text="Close breadcrumb example"
//        />
//      </ScrollView>
//    );
//  },
//  componentWillMount: function() {
//    this.navBarRouteMapper = {
//      rightContentForRoute: function(route, navigator) {
//        return null;
//      },
//      titleContentForRoute: function(route, navigator) {
//        return (
//          <TouchableOpacity
//            onPress={() => navigator.push(this.getRandomRoute())}>
//            <View>
//              <Text style={styles.titleText}>{route.title}</Text>
//            </View>
//          </TouchableOpacity>
//        );
//      },
//      iconForRoute: function(route, navigator) {
//        return (
//          <TouchableOpacity onPress={() => {
//            navigator.popToRoute(route);
//          }}>
//            <View style={styles.crumbIconPlaceholder} />
//          </TouchableOpacity>
//        );
//      },
//      separatorForRoute: function(route, navigator) {
//        return (
//          <TouchableOpacity onPress={navigator.pop}>
//            <View style={styles.crumbSeparatorPlaceholder} />
//          </TouchableOpacity>
//        );
//      }
//    };
//  },
});

var styles = StyleSheet.create({
  container: {
  //  marginTop: 65,
    marginTop: 25,
    flex: 1,
    backgroundColor: '#F5FCFF',
  },
  welcome: {
    fontSize: 20,
    textAlign: 'center',
    margin: 10,
  },
  instructions: {
    textAlign: 'center',
    color: '#333333',
  },
  listView: {
    paddingTop: 20,
    backgroundColor: '#F5FCFF',
  },
  todoElement: {
    paddingTop: 10,
    paddingBottom: 20,
    paddingLeft: 5,
    textAlign: 'left',
    borderBottomWidth: 1,
  },
  tabbed: {
    textAlign: 'left',
    left: 15,
  },
 scene: {
    paddingTop: 50,
    flex: 1,
  },
  button: {
    backgroundColor: 'white',
    padding: 15,
    borderBottomWidth: 1 / 10,
    borderBottomColor: '#CDCDCD',
  },
  buttonText: {
    fontSize: 17,
    fontWeight: '500',
  },
  //container: {
  //  overflow: 'hidden',
  //  backgroundColor: '#dddddd',
  //  flex: 1,
  //},
  titleText: {
    fontSize: 18,
    color: '#666666',
    textAlign: 'center',
    fontWeight: 'bold',
    lineHeight: 32,
  },
  crumbIconPlaceholder: {
    flex: 1,
    backgroundColor: '#666666',
  },
  crumbSeparatorPlaceholder: {
    flex: 1,
    backgroundColor: '#aaaaaa',
  },
});

AppRegistry.registerComponent('doorlock-app', () => doorlockapp);

