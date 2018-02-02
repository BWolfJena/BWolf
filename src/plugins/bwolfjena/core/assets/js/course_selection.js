var sortableList = document.getElementById('sortableCourseList');
var itemCount = $('#sortableCourseList').children().length;

function saveOrder() {
    $.request('onSaveOrder',{
        data: {
            order: sortable.toArray(),
        },
    });
}

var sortable =   Sortable.create(sortableList, {
  handle: '.drag-handle',
  onEnd: function(event) {
    var items = $('#sortableCourseList').find('.rank').each(function(index) {
      $(this).text(itemCount-index);
    });
    saveOrder();
  }
});

$(document).ready(function(){
  $('#saveOrderButton').on('click touchstart', function(event){
    event.preventDefault();
    saveOrder();
  });
})