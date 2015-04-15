'use strict';

var React = require('react-native');

var {
  AppRegistry,
  StyleSheet,
  Text,
  View,
  ListView,
  NavigatorIOS,
  Component,
  TouchableHighlight,
  TextInput,
  TabBarIOS,
  Image,
} = React;

//var GetAll = require('./GetAll');
//var GetIncomplete = require('./GetIncomplete');
//var CreateTask = require('./CreateTask');

//touchable getall and incomplete (for marking complete)
//Create page
//Add USER to search

var REQUEST_URL = "http://api.localhost";
var API_KEY = "test";
var TabBarItemIOS = TabBarIOS.Item;
var serialize = function (data) {
  return Object.keys(data).map(function (keyName) {
    return encodeURIComponent(keyName) + '=' + encodeURIComponent(data[keyName])
  }).join('&');
};
//var Icon = require('FAKIconImage');
//var SMXTabBarIOS = require('SMXTabBarIOS');
//var SMXTabBarItemIOS = SMXTabBarIOS.Item;

var GetLoggedInUser = React.createClass({
  getInitialState: function() {
      return {
        //loaded: false,
        //isLoggedIn: false,
        //searchGetAll: 'asdf',
        //getUser: '',
        selectedTab: 'homeTab',
        userResponseData: '',
        lockStatusResponseData: '',
      };
  },
  componentDidMount: function() {
    this.getHomeInfo();
    this.getUserInfo();
    this.getLockInfo();
  },
  render: function() {
    return (
      <TabBarIOS
        selectedTab={this.state.selectedTab}>
        <TabBarItemIOS
          name="homeTab"
          icon={_ix_DEPRECATED('search')}
          accessibilityLabel="Home Tab"
          selected={this.state.selectedTab === 'homeTab'}
          onPress={() => {
            this.setState({
              selectedTab: 'homeTab'
            });
          }}>
          {this.renderHome()}
        </TabBarItemIOS>
        <TabBarItemIOS
          accessibilityLabel="User Info Tab"
          name="userTab"
          icon={_ix_DEPRECATED('history')}
          badgeValue={this.state.notifCount ? String(this.state.notifCount) : null}
          selected={this.state.selectedTab === 'userTab'}
          onPress={() => {
            this.setState({
              selectedTab: 'userTab',
            });
          }}>
          {this.renderUserInfo()}
        </TabBarItemIOS>
        <TabBarItemIOS
          name="lockTab"
          icon={_ix_DEPRECATED('more')}
          accessibilityLabel="Lock Tab"
          selected={this.state.selectedTab === 'lockTab'}
          onPress={() => {
            this.setState({
              selectedTab: 'lockTab',
              title: 'testing',
            });
          }}>
          {this.renderLock()}
        </TabBarItemIOS>
      </TabBarIOS>
    );

  },
  _renderContent: function(color: string, pageText: string) {
    return (
      <View style={[styles.tabContent, {backgroundColor: color}]}>
        <Text style={styles.tabText}>{pageText}</Text>
        <Text style={styles.tabText}>re-renders of this tab</Text>
        <Text style={styles.tabText}>{this.title}</Text>
        <Text style={styles.tabText}>{this.props.responseData}</Text>
      </View>
    );
  },
  renderHome: function(){
        //<TouchableHighlight onPress={this.props.navigator.pop()}>
    console.log("all the props: " + JSON.stringify(this.props));
    return (
      <View style={styles.container}>
        <Text>HomeTab</Text>
        <TouchableHighlight onPress={this.props}>
          <Text>LOGOUT</Text>
        </TouchableHighlight>
      </View>
    );
    //return (
    //  <View style={styles.container}>
    //    <Text>HomeTab</Text>
    //    <TouchableHighlight onPress={this.props.navigator.pop()}>
    //      <Text>LOGOUT</Text>
    //    </TouchableHighlight>
    //  </View>
    //);
  },
  renderUserInfo: function(){
    return (
      <View style={styles.container}>
        <Text>UserIngoTab</Text>
        <Text>{JSON.stringify(this.state.userResponseData)}</Text>
      </View>
    );
  },
  renderLock: function(){
    return (
      <View style={styles.container}>
        <Text>LockTab</Text>
        <Text>{JSON.stringify(this.state.lockStatusResponseData)}</Text>
      </View>
    );
  },
  getHomeInfo: function() {
    //TODO fetch the info and save it to the state
  },
  getUserInfo: function() {
    fetch((REQUEST_URL + "/GetUserInfo"), {
      method: 'POST', 
      headers: {
        'x-doorlock-api-key': API_KEY,
        'sid': this.props.sid,
      },
    })
       .then((response) => response.json())
       .then((responseDatas) => {
         this.setState({
           loaded: true,
           userResponseData: responseDatas,
           //responseDatass: response,
           //isLoggedIn: true,
         });
         console.log(responseDatas);
       })
      .done();
  },
  getLockInfo: function() {
    fetch((REQUEST_URL + "/LockStatus"), {
      method: 'POST', 
      headers: {
        'x-doorlock-api-key': API_KEY,
        'sid': this.props.sid,
      },
    })
       .then((response) => response.json())
       .then((responseDatas) => {
         this.setState({
           loaded: true,
           lockStatusResponseData: responseDatas,
           //responseDatass: response,
           //isLoggedIn: true,
         });
         console.log(responseDatas);
       })
      .done();
  },
});

var styles = StyleSheet.create({
  container: {
    marginTop: 45,
    flex: 1,
    backgroundColor: '#F5FCFF',
  },
  button: {
    marginBottom: 20,
    height: 30,
    backgroundColor: '#48BBEC',
  },
  textbox: {
    backgroundColor: '#FFFFFF',
    height: 30,
  },
  tabText: {
    backgroundColor: '#FFFFFF',
    height: 300,
  },
  icon: {
    width: 15,
    height: 15,
  },
});
function _ix_DEPRECATED(imageUri) {
    return {
      uri: imageUri,
      isStatic: true,
    };
}

module.exports = GetLoggedInUser;

