import * as $ from "jquery"
$(()=>{
  $("#templateOverviewFilter").on("keyup", (event) => {
    let value = $(event.target).val().toLowerCase();
    $("#templateOverviewTable tr").each(function (index, elem) {
      $(elem).toggle($(elem).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $('.selectpicker').selectpicker({
    style: 'btn btn-default',
    liveSearch:true
  });
});
