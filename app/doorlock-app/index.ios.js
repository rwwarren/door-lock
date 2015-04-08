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
  //Fetch,
  //FormData,
} = React;

//var HomePage = require('./HomePage');
var GetLoggedInUser = require('./GetLoggedInUser');

var REQUEST_URL = "http://api.localhost/login";

var serialize = function (data) {
  return Object.keys(data).map(function (keyName) {
    return encodeURIComponent(keyName) + '=' + encodeURIComponent(data[keyName])
  }).join('&');
};
var doorlockapp = React.createClass({
  getInitialState: function() {
      return {
        isLoggedIn: false,
        username: '',
        password: '',
        token: '',
        responseData: '',
        responseDatass: '',
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
    return (
      <NavigatorIOS
        style={styles.container}
        initialRoute={{
          title: 'Home Page',
          rightButtonTitle: 'Logout',
          onRightButtonPress: () => this.props.navigator.pop(),
          component: GetLoggedInUser,
          passProps: {responseData: this.state.responseData},
      }}/>
    );
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
    return (
      <View style={styles.container}>
        <Text>Please Login</Text>
        <TextInput
          style={styles.textbox}
          placeholder='Username'
          value={this.state.getUser}
          onChange={this.setUser.bind(this)}/>
        <TextInput
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
    this.attemptLogin();
    //if (this.state.)
    //this.setState({
    //  isLoggedIn: true,
    //});

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
    //var FormData = require('form-data');
    //var form = new FormData();
    //var form = new FormData();
    //FormData.setFormData('a', 'a');
    //console.log("formdata: " + this.formData);
    //console.log("formdata: " + this.FormData);
    //console.log("formdata: " + FormData);
    //FormData.append('a', 1);
    //form.append('a', 1);
    var form = {};
    form['a'] = 'a';
    var json = "username:ryan";
    fetch(REQUEST_URL, {
      method: 'POST', 
      headers: {
      //header: {
        //'Accept': 'application/json',
        //'Content-Type': 'application/json',
        'x-doorlock-api-key': 'test',
        'X-DoorLock-Api-Key': 'test',
        //'DoorLock-Api-Key': 'test',
        'sid': 'asdf'
      },
      body: serialize({
        'username': this.state.username,
        'password': this.state.password,
      }),
      //body: serialize({json: json}),
//      {
//
//      },
//      body: {
//        'usernmae' : 'ryan',
//        
//      },
      requestBody: {
        'usernmae' : 'ryan',
        form,
      }
      //body: JSON.stringify({
      //  'username': this.state.username,
      //  'password': this.state.password,
      //  'token': this.state.token,
      //  //'username=ryan',

      //})
    })
       .then((response) => response.json())
       .then((responseDatas) => {
         this.setState({
           loaded: true,
           responseData: responseDatas,
           //responseDatass: response,
           //isLoggedIn: true,
         });
       })
      .done();
      console.log(this.state.responseData);
      console.log(this.state.username);
      //console.log(this.state.responseDatass);

  },
//  attemptLogin: function() {
//    fetch(REQUEST_URL)
//       .then((response) => response.json())
//       .then((responseDatas) => {
//         this.setState({
//           loaded: true,
//           responseData: responseDatas,
//           isLoggedIn: true,
//         });
//       })
//      .done();
//
//  },
});

var styles = StyleSheet.create({
  container: {
  //  marginTop: 65,
    marginTop: 25,
    flex: 1,
    backgroundColor: '#F5FCFF',
  },
//  welcome: {
//    fontSize: 20,
//    textAlign: 'center',
//    margin: 10,
//  },
//  instructions: {
//    textAlign: 'center',
//    color: '#333333',
//  },
//  listView: {
//    paddingTop: 20,
//    backgroundColor: '#F5FCFF',
//  },
//  todoElement: {
//    paddingTop: 10,
//    paddingBottom: 20,
//    paddingLeft: 5,
//    textAlign: 'left',
//    borderBottomWidth: 1,
//  },
//  tabbed: {
//    textAlign: 'left',
//    left: 15,
//  },
// scene: {
//    paddingTop: 50,
//    flex: 1,
//  },
//  button: {
//    backgroundColor: 'white',
//    padding: 15,
//    borderBottomWidth: 1 / 10,
//    borderBottomColor: '#CDCDCD',
//  },
//  buttonText: {
//    fontSize: 17,
//    fontWeight: '500',
//  },
  //container: {
  //  overflow: 'hidden',
  //  backgroundColor: '#dddddd',
  //  flex: 1,
  //},
//  titleText: {
//    fontSize: 18,
//    color: '#666666',
//    textAlign: 'center',
//    fontWeight: 'bold',
//    lineHeight: 32,
//  },
//  crumbIconPlaceholder: {
//    flex: 1,
//    backgroundColor: '#666666',
//  },
//  crumbSeparatorPlaceholder: {
//    flex: 1,
//    backgroundColor: '#aaaaaa',
//  },
  textbox: {
    backgroundColor: '#FFFFFF',
    height: 30,
  },
});

AppRegistry.registerComponent('doorlock-app', () => doorlockapp);

