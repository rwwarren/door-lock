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
  navigator,
  ScrollView,
  NavButton,
  TouchableOpacity,
  TouchableHighlight,
  TextInput,
  AsyncStorage,
} = React;

var LoginPage = require('./LoginPage');
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
            this.setState({'sid': value});
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
    var prev = this.props.route;
    console.log("this route: " + this.props.route);
    return(
        <Navigator
          initialRoute={{name: 'My First Scene', index: 0, navigator}}
          renderScene={(route, navigator) =>
            <LoginPage
              sid={this.state.sid}
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
        'x-doorlock-api-key': API_KEY,
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
         });
       })
      .done();
      console.log(this.state.responseData);
      console.log(this.state.username);
      console.log(this.state.responseData.success);

  },
  checkLogin: function() {
    fetch((REQUEST_URL + "/IsLoggedIn"), {
      method: 'POST', 
      //method: 'GET', 
      headers: {
        'x-doorlock-api-key': API_KEY,
        'sid': this.state.sid,
      },
    })
       .then((response) => response.json())
       .then((responseDatas) => {
        console.log("While checking the login: " + responseDatas);
        console.log("While checking the login: " + JSON.stringify(responseDatas));
         this.setState({
           loaded: true,
           responseData: responseDatas,
         });
       })
      .done();
      console.log(this.state.username);
      console.log("success?? " + this.state.responseData.success);

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
