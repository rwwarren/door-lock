//$(function() {
//  $("ul.connectedSortable").sortable({
//    //connectWith: "ul", items: "li:not(.currentUser)", update: function(event, ui) {
//    connectWith: "ul", items: "li:not(.currentUser)", update: function(event, ui) {
//      if(ui.sender) {
//        var updated = ui.item.attr('id');
//        var old = ui.item.attr('class');
//        var type = event.target.id;
//        $.ajax({
//          type: "POST", url: "changeUser.php", data: {type: type, user: updated}, statusCode: {
//            200: function() {
//              ui.item.removeClass(old);
//              ui.item.addClass(type);
//            }, 400: function() {
//              alert("Error! Please refresh the page!");
//              window.location.reload();
//            }, 403: function() {
//              alert("Can not make changes! Please refresh the page!");
//              document.location.href = '/'
//            }
//          }
//        });
//      }
//    }
//  });
//  $("#admin, #active, #inactive").disableSelection();
//});

$(function() {
  $("ul.connectedSortable").sortable({
    dropOnEmpty: true,
    connectWith: "ul", items: "li:not(.currentUser)", update: function(event, ui) {
    //connectWith: "ul", items: "li:not(.currentUser)", containment: "document", update: function(event, ui) {
      if(ui.sender) {
        console.log("selected");
      }
    }
  }).disableSelection();
});