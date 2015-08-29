
'use strict';

//var NestedStatus = require('../../node_modules/react-nested-status/index');
//var NestedStatus = NestedStatus;

var FourOFour = React.createClass({
    //mixins: [NestedStatus],

    render: function(){
        return (
            //<NestedStatus code={404}>
                <div>
                    404 nott found :(
                </div>
            //</NestedStatus>
        );
    }
});

module.exports = FourOFour;