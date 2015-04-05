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

var TabBarItemIOS = TabBarIOS.Item;
//var Icon = require('FAKIconImage');
//var SMXTabBarIOS = require('SMXTabBarIOS');
//var SMXTabBarItemIOS = SMXTabBarIOS.Item;

var GetLoggedInUser = React.createClass({
  getInitialState: function() {
      return {
        loaded: false,
        isLoggedIn: false,
        //searchGetAll: 'asdf',
        getUser: '',
        selectedTab: 'homeTab',
      };
      //this.setState({
      //    loaded: true,
      //});
  },
  render: function() {
          //icon={_ix_DEPRECATED('history')}
          //icon={'HomeIcon.png'}
          //icon={this.loadImage("home")}
          //icon={_ix_DEPRECATED('searchs')}
          //icon={require("image!/Users/ryan/Documents/door-lock/app/doorlock-app/homeIcon.png")}
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
              selectedTab: 'homeTab',
            });
          }}>
          {this._renderContent('#414A8C', 'Home Tab')}
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
              //notifCount: this.state.notifCount + 1,
            });
          }}>
          {this._renderContent('#783E33', 'User Tab')}
        </TabBarItemIOS>
        <TabBarItemIOS
          name="lockTab"
          icon={_ix_DEPRECATED('more')}
          accessibilityLabel="Lock Tab"
          selected={this.state.selectedTab === 'lockTab'}
          onPress={() => {
            this.setState({
              selectedTab: 'lockTab',
              //presses: this.state.presses + 1
            });
          }}>
          {this._renderContent('#21551C', 'Lock Tab')}
        </TabBarItemIOS>
      </TabBarIOS>
    );

//return (
//      <SMXTabBarIOS
//        selectedTab={this.state.selectedTab}
//        tintColor={'#c1d82f'}
//        barTintColor={'#000000'}
//        styles={styles.tabBar}>
//        <SMXTabBarItemIOS
//          name="home"
//          iconName={'ion|ios-home-outline'}
//          title={''}
//          iconSize={32}
//          accessibilityLabel="Home Tab"
//          selected={this.state.selectedTab === 'home'}
//          onPress={() => {
//            this.setState({
//              selectedTab: 'home',
//            });
//          }}>
//          {this._renderContent()}
//        </SMXTabBarItemIOS>
//        <SMXTabBarItemIOS
//            name="articles"
//            iconName={'ion|ios-paper-outline'}
//            title={''}
//            iconSize={32}
//            accessibilityLabel="Articles Tab"
//            selected={this.state.selectedTab === 'articles'}
//            onPress={() => {
//            this.setState({
//              selectedTab: 'articles',
//            });
//          }}>
//          {this._renderContent()}
//        </SMXTabBarItemIOS>
//        <SMXTabBarItemIOS
//            name="messages"
//            iconName={'ion|chatboxes'}
//            title={''}
//            iconSize={32}
//            accessibilityLabel="Messages Tab"
//            selected={this.state.selectedTab === 'messages'}
//            onPress={() => {
//            this.setState({
//              selectedTab: 'messages',
//            });
//          }}>
//          {this._renderContent()}
//        </SMXTabBarItemIOS>
//        <SMXTabBarItemIOS
//            name="settings"
//            iconName={'ion|ios-gear'}
//            title={''}
//            iconSize={32}
//            accessibilityLabel="Settings Tab"
//            selected={this.state.selectedTab === 'settings'}
//            onPress={() => {
//            this.setState({
//              selectedTab: 'settings',
//            });
//          }}>
//          {this._renderContent()}
//        </SMXTabBarItemIOS>
//      </SMXTabBarIOS>
//    );
  },
  _renderContent: function(color: string, pageText: string) {
    return (
      <View style={[styles.tabContent, {backgroundColor: color}]}>
        <Text style={styles.tabText}>{pageText}</Text>
        <Text style={styles.tabText}>{this.state.presses} re-renders of this tab</Text>
      </View>
    );
  },
  loadImage: function(image: string){
//    <Image
//      style={styles.icon}
//      source={require('image!homeIcon')}
//      source={require('homeIcon.png')}
        //source={{uri: 'http://facebook.github.io/react/img/logo_og.png'}}
//      />
    return (
      <Image
        style={styles.icon}
        source={{uri: '/Users/ryan/Documents/door-lock/app/doorlock-app/homeIcon.png'}}
        />
    );
  }
});

var styles = StyleSheet.create({
  container: {
    marginTop: 65,
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

