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
} = React;

var HomePage = require('./HomePage');

var doorlockapp = React.createClass({
  render: function() {
    //if (!this.state.loaded) {
    //  return <Text>asdf</Text>;
    //}
//      <NavigatorIOS
//        style={styles.container}
//        initialRoute={{
//          title: 'Home Page',
//          component: HomePage,
//      }}/>
    return(
<Navigator
    initialRoute={{name: 'My First Scene', index: 0}}
    renderScene={(route, navigator) =>
      <HomePage
        name={route.name}
        onForward={() => {
          var nextIndex = route.index + 1;
          navigator.push({
            name: 'Scene ' + nextIndex,
            index: nextIndex,
          });
        }}
        onBack={() => {
          if (route.index > 0) {
            navigator.pop();
          }
        }}
      />
    }
  />

     );
  },
});

var styles = StyleSheet.create({
  container: {
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
});

AppRegistry.registerComponent('doorlock-app', () => doorlockapp);
