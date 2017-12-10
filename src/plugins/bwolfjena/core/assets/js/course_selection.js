var sortableList = document.getElementById('sortableCourseList');
var itemCount = $('#sortableCourseList').children().length;

var sortable =   Sortable.create(sortableList, {
  handle: '.drag-handle',
  onEnd: function(event) {
    var items = $('#sortableCourseList').find('.rank').each(function(index) {
      $(this).text(itemCount-index);
    });
  }
});

$(document).ready(function(){
  $('#saveOrderButton').on('click touchstart', function(event){
    event.preventDefault();
    $.request('onSaveOrder',{
      data: {
        order: sortable.toArray(),
      },
    });
  });
})