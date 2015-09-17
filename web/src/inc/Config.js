'use strict';
var common = require('./Common');

var Config = React.createClass({
    componentDidMount: function() {
      $.ajax({
        url: common.API_URL + common.CONFIG,
        type: "POST",
        data: {sid: $.cookie("sid")},
        dataType: "json",
        success: function(result) {
          console.log(result);
          //React.render(result, document.body);
          this.setState({
            config: result,
            loaded: true
          });
        }.bind(this),
        error: function(xhr, status, error) {
          console.log(status);
          console.log(error);
        }
      });
    },
    getInitialState: function() {
      return {
        config: '',
        loaded: false
      };
    },
    render: function() {
      //todo fix this so it works
      //    {JSON.parse(this.state.config)}
      return (
        <div>
          {JSON.stringify(this.state.config, null, '\t')}
        </div>
      );
      //return React.DOM.body(
      //  this.state.config
      //);
    }
  })
  ;

module.exports = Config;