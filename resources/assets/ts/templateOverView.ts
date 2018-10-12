import * as bootbox from "bootbox";

import {translate} from "./Translate";
$(()=>{
  $("#templateOverviewFilter").on("keyup", (event) => {
    let value = $(event.target).val().toLowerCase();
    $("#templateOverviewTable tr").each(function (index, elem) {
      $(elem).toggle($(elem).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $('.selectpicker').selectpicker({
    style: 'btn btn-secondary',
    liveSearch:true
  });

  $('.delete-template').on('click', (event) =>  {
    let target = event.currentTarget;
    let templateId = $(target).data('id');
    let templateName = $(target).data('templatename');

    // Initialise modal and show loading icon and message
    var dialog = <any> bootbox.confirm({
      title: '<h4 class="alert alert-danger text-center">' + translate('deleteTemplate') + '</h4>',
      size: 'large',
      message: '<p>' + translate('deleteTemplateMessage') +  '</p><p class="text-danger">'+ templateName + '</p>',
      buttons: {
        confirm: {
          label:'<span class="glyphicon glyphicon-ok" ></span>',
          className:'btn-danger'
        },
        cancel: {
          label:'<span class="glyphicon glyphicon-remove" ></span>',
          className: 'btn-success'
        }
      },
      callback:(result) => {
        if(result){
          $('#delete-template-' + templateId).submit();
        }
      }
    });
  });
});
