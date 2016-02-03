$(function() {
  $("ul.connectedSortable").sortable({
    dropOnEmpty: true,
    connectWith: "ul", items: "li:not(.currentUser)", update: function(event, ui) {
      if(ui.sender) {
        //TODO make sure username is correct and not tampored with
        console.log("selected");
        //TODO validate this.... not the best way to do this
        var username = ui.item[0].id;
        // var username = ui.item[0].innerText;
        var type = event.target.className;
        console.log("type: " + type);
        console.log(username);
        $.ajax({
          //TODO define this
          url: "http://localhost:1337/api.localhost/UpdateOtherUser",
          // url: common.API_URL + common.ADMIN_UPDATE,
          type: "POST",
          contentType: "application/json",
          data: JSON.stringify({
            sid: $.cookie("sid")
          }),
          dataType: "json",
          success: function(result) {
            console.log(result);
            // this.setState({
              // adminData: result,
              // loaded: true
            // });
          }.bind(this),
          error: function(xhr, status, error) {
            console.log(status);
            console.log(error);
          }
        });
      }
    }
  }).disableSelection();
});
