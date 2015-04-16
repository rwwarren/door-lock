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
  //Fetch,
  //FormData,
} = React;


var GetLoggedInUser = require('./GetLoggedInUser');
var REQUEST_URL = "http://api.localhost";
var STORAGE_KEY = "@doorlock:sid";
var API_KEY = "test";

var serialize = function (data) {
  return Object.keys(data).map(function (keyName) {
    return encodeURIComponent(keyName) + '=' + encodeURIComponent(data[keyName])
  }).join('&');
};
var LoginPage = React.createClass({
  componentDidMount: function() {
    AsyncStorage.getItem(STORAGE_KEY)
        .then((value) => {
          if (value !== null){
            this.setState({'sid': value});
            //this.setState({selectedValue: value});
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
    //backButtonTitle: 'Logout',
    //console.log("Checking if loggedin: " + (this.state.responseData.success === "1"));
    //if(this.state.responseData.success === "1") {
          //onRightButtonPress: () => this.props.navigator.pop(),
          //onRightButtonPress: () => this.props.navigator.replace(prev),
    var prev = this.props.route;
    console.log("this route: " + this.props.route);
        //passProps: {deck: deck, onPress: onPress},
      //this.props.navigator.push({
      //return navigator.push({
      //return this.props.navigator.push({
      //  title: "test",
      //  component: GetLoggedInUser,
      //});
            //if (route.index > 0) {
            //  navigator.pop();
            //}
      console.log("LOGIN all the props: " + JSON.stringify(this.props));
      return(
        <Navigator
          initialRoute={{name: 'My First Scene', index: 0, navigator}}
          renderScene={(route, navigator) =>
            <GetLoggedInUser
              sid={this.props.sid}
              username={this.state.username}
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
      //return(
      //  <GetLoggedInUser
      //    name={"asdf"}
      //    navigator={this.props.navigator}
      //    onBack={() => {
      //        navigator.pop();
      //    }}
      //  />
      //);
      //    props={"res":"asdf"}
    //return(
    //    <Navigator
    //      initialRoute={{name: 'My First Scene', index: 0}}
    //      renderScene={(route, navigator) =>
    //        <GetLoggedInUser
    //          name={route.name}
    //          onForward={() => {
    //            var nextIndex = route.index + 1;
    //            navigator.push({
    //              name: 'Scene ' + nextIndex,
    //              index: nextIndex,
    //            });
    //          }}
    //          onBack={() => {
    //            if (route.index > 0) {
    //              navigator.pop();
    //            }
    //          }}
    //        />
    //      }
    //    />
    //);
    //return (
    //  <NavigatorIOS
    //    style={styles.container}
    //    ref="nav"
    //    initialRoute={{
    //      title: 'Home Page',
    //      rightButtonTitle: 'Logout',
    //      onRightButtonPress: () => this.props.navigator.pop(),
    //      component: GetLoggedInUser,
    //      passProps: {responseData: this.state.responseData},
    //  }}/>
    //);
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
           username: responseDatas.LoggedIn,
         });
       })
      .done();
      //console.log("While checking the login: " + this.state.responseData);
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

module.exports = LoginPage;

