import * as $ from "jquery"
$(window).on('load',()=>{
  $('.shiftTypeSelector').selectpicker({
    style: 'btn btn-default',
    liveSearch:true
  });
});

$('.shiftTypeSelector').change((event)=>{
  let selectElement = $(event.target);
  let selectedValue = selectElement.val();
  if(selectedValue == -1){
    return;
  }
  let submitBtn = selectElement.data('submit');
  console.log($('#' + submitBtn));
  $('#' + submitBtn).submit();
});
