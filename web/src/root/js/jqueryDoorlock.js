$(function() {
  $("ul.connectedSortable").sortable({
    dropOnEmpty: true,
    connectWith: "ul", items: "li:not(.currentUser)", update: function(event, ui) {
      if(ui.sender) {
        console.log("selected");
      }
    }
  }).disableSelection();
});
