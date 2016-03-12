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

var GetLoggedInUser = React.createClass({
  getInitialState: function() {
      return {
        loaded: false,
        selectedTab: 'homeTab',
        userResponseData: '',
        lockStatusResponseData: '',
        name: '',
        email: '',
        card: '',
        authy: '',
      };
  },
  componentDidMount: function() {
    this.getHomeInfo();
    this.getUserInfo();
    this.getLockInfo();
  },
  render: function() {
    if(!this.state.loaded){
      return (<Text>Loading....</Text>);
    }
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
    console.log("all the props: " + JSON.stringify(this.props));
    return (
      <View style={styles.container}>
        <Text style={styles.pageTitle}>HomeTab</Text>

        <Text>Welcome: {this.state.userResponseData.Name}</Text>
        <Text></Text>
        <TouchableHighlight onPress={this.props}>
          <Text>Click to logout</Text>
        </TouchableHighlight>
      </View>
    );
  },
  renderUserInfo: function(){
    return (
      <View style={styles.container}>
        <Text style={styles.pageTitle}>User modification</Text>
        <Text>Username:</Text>
        <Text style={styles.value}>{this.props.username}</Text>
        <Text style={styles.editTitle}>Name:</Text>
        <TextInput
          style={styles.textbox}
          placeholder={this.state.userResponseData.Name}
          value={this.state.getToken}
          onChange={this.setToken.bind(this)}/>
        <Text style={styles.editTitle}>Email:</Text>
        <TextInput
          style={styles.textbox}
          placeholder={this.state.userResponseData.Email}
          keyboardType={'email-address'}
          value={this.state.getToken}
          onChange={this.setToken.bind(this)}/>
        <Text style={styles.editTitle}>CardID:</Text>
        <TextInput
          style={styles.textbox}
          placeholder={this.state.userResponseData.CardID}
          keyboardType={'numeric'}
          value={this.state.getToken}
          onChange={this.setToken.bind(this)}/>
        <Text style={styles.editTitle}>AuthyID:</Text>
        <TextInput
          style={styles.textbox}
          keyboardType={'numeric'}
          placeholder={this.state.userResponseData.AuthyID}
          value={this.state.getToken}
          onChange={this.setToken.bind(this)}/>
        <TouchableHighlight onPress={this.submitChanges}>
          <Text>Submit Changes</Text>
        </TouchableHighlight>
      </View>
    );
  },
  setName: function(event) {
    this.setState({ name: event.nativeEvent.text });
  },
  setEmail: function(event) {
    this.setState({ email: event.nativeEvent.text });
  },
  setCard: function(event) {
    this.setState({ card: event.nativeEvent.text });
  },
  setAuthy: function(event) {
    this.setState({ authy: event.nativeEvent.text });
  },
  renderLock: function(){
    return (
      <View style={styles.container}>
        <Text style={styles.pageTitle}>LockTab</Text>
        <Text>Current Lock Status:</Text>
        <Text></Text>
        <Text>{this.state.lockStatusResponseData.Status}</Text>
        <Text></Text>
        <TouchableHighlight onPress={this.lockUnlock}>
          <Text>Click here to {this.state.lockStatusResponseData.isLocked == 1 ? "Unlock" : "Lock"}</Text>
        </TouchableHighlight>
      </View>
    );
  },
  setToken: function() {

  },
  lockUnlock: function() {

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
  pageTitle: {
    textAlign: 'center',
    fontSize: 25,
    marginBottom: 10,
  },
  textbox: {
    backgroundColor: '#FFFFFF',
    height: 30,
    marginBottom: 10,
  },
  editTitle: {
    position: 'relative',
  },
  value : {
    fontWeight: 'bold',
    paddingBottom: 50,
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
