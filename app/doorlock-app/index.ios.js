/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 */
'use strict';

var React = require('react-native');
var FormData = require('react-form-data');

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
  TouchableOpacity,
  TouchableHighlight,
  TextInput,
  AsyncStorage,
  //Fetch,
  //FormData,
} = React;

//var HomePage = require('./HomePage');
var GetLoggedInUser = require('./GetLoggedInUser');

var REQUEST_URL = "http://api.localhost";
var STORAGE_KEY = "@doorlock:sid";
var API_KEY = "test";

var serialize = function (data) {
  return Object.keys(data).map(function (keyName) {
    return encodeURIComponent(keyName) + '=' + encodeURIComponent(data[keyName])
  }).join('&');
};
var doorlockapp = React.createClass({
  componentDidMount: function() {
    AsyncStorage.getItem(STORAGE_KEY)
        .then((value) => {
          if (value !== null){
            this.setState({selectedValue: value});
            console.log("found a state: " + value);
            this.checkLogin();
          }
        })
  },
  getInitialState: function() {
      var mySid = makeid();
      return {
        loaded: false,
        isLoggedIn: false,
        username: '',
        password: '',
        token: '',
        responseData: '',
        responseDatass: '',
        sid: mySid,
      }
  },
  render: function() {
    if (!this.state.isLoggedIn) {
      return this.renderLoginPage();
    }
      return this.userPage();
  },
  userPage: function(){
    //FIX THIS LOGIN
    //USE NAVIGATOR
    //backButtonTitle: 'Logout',
    //console.log("Checking if loggedin: " + (this.state.responseData.success === "1"));
    //if(this.state.responseData.success === "1") {
          //onRightButtonPress: () => this.props.navigator.pop(),
    var prev = this.props.route;
    console.log(this.props.route);
    return (
      <NavigatorIOS
        style={styles.container}
        initialRoute={{
          title: 'Home Page',
          rightButtonTitle: 'Logout',
          onRightButtonPress: () => this.props.navigator.replace(prev),
          component: GetLoggedInUser,
          passProps: {responseData: this.state.responseData},
      }}/>
    );
    //}
    //  this.props.navigator.push({
    //  title: 'Results Incomplete',
    //  component: GetLoggedInUser,
    //  passProps: {
    //    getUser: this.state.getUser,
    //    getPassword: this.state.getPassword,
    //    getToken: this.state.getToken,
    //  },
    //});
  },
  renderLoginPage: function() {
    if(this.state.loaded && this.state.responseData.success === "1"){
      AsyncStorage.setItem(STORAGE_KEY, this.state.sid).done();
      return this.userPage();
    }
    return (
      <View style={styles.container}>
        <Text>Please Login</Text>
        <TextInput
          style={styles.textbox}
          placeholder='Username'
          value={this.state.getUser}
          onChange={this.setUser.bind(this)}/>
        <TextInput
          secureTextEntry={true}
          style={styles.textbox}
          placeholder='Password'
          value={this.state.getPassword}
          onChange={this.setPassword.bind(this)}/>
        <TextInput
          style={styles.textbox}
          placeholder='Token'
          value={this.state.getToken}
          onChange={this.setToken.bind(this)}/>
        <TouchableHighlight onPress={this.renderLogin}>
          <Text>LOGIN</Text>
        </TouchableHighlight>
      </View>
    );
  },
  renderLogin: function(){
    if(this.state.username.length > 0 && this.state.password.length > 0){
      this.attemptLogin();
    }
  },
  setUser: function(event) {
    this.setState({ username: event.nativeEvent.text });
  },
  setPassword: function(event) {
    this.setState({ password: event.nativeEvent.text });
  },
  setToken: function(event) {
    this.setState({ token: event.nativeEvent.text });
  },
  attemptLogin: function() {
    fetch((REQUEST_URL + "/login"), {
      method: 'POST', 
      headers: {
      //header: {
        //'Accept': 'application/json',
        //'Content-Type': 'application/json',
        'x-doorlock-api-key': API_KEY,
        //'X-DoorLock-Api-Key': 'test',
        //'DoorLock-Api-Key': 'test',
        'sid': this.state.sid,
      },
      body: serialize({
        'username': this.state.username,
        'password': this.state.password,
      }),
    })
       .then((response) => response.json())
       .then((responseDatas) => {
         this.setState({
           loaded: true,
           responseData: responseDatas,
           //responseDatass: response,
           //isLoggedIn: true,
         });
         //console.log(response);
       })
      .done();
      console.log(this.state.responseData);
      console.log(this.state.username);
      console.log(this.state.responseData.success);
      //console.log(this.state.responseDatass);

  },
  checkLogin: function() {
    fetch((REQUEST_URL + "/IsLoggedIn"), {
      method: 'GET', 
      headers: {
        'x-doorlock-api-key': API_KEY,
        'sid': this.state.sid,
      },
    })
       .then((response) => response.json())
       .then((responseDatas) => {
         this.setState({
           loaded: true,
           responseData: responseDatas,
         });
       })
      .done();
      console.log("While checking the login: " + this.state.responseData);
      console.log(this.state.username);
      console.log("success?? " + this.state.responseData.success);
      //console.log(this.state.responseDatass);

  },
});
function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for( var i = 0; i < 103; i++ ) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

var styles = StyleSheet.create({
  container: {
    marginTop: 25,
    flex: 1,
    backgroundColor: '#F5FCFF',
  },
  textbox: {
    backgroundColor: '#FFFFFF',
    height: 30,
  },
});

AppRegistry.registerComponent('doorlock-app', () => doorlockapp);

