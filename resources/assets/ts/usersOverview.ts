import * as bootbox from "bootbox";

import {translate} from "./Translate";

$(()=>{
    $(".userOverviewFilter").on("keyup", (event) => {
        let value = $(event.target).val().toLowerCase();
        $("#userOverviewTable tr").each(function (index, elem) {
            $(elem).toggle($(elem).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $('.change-user-status-form > .selectpicker').selectpicker({
        style: 'btn btn-secondary btn-sm',
        liveSearch:true
    });

  $('.editUserFormselectpicker').selectpicker({
    style: 'btn btn-secondary btn-sm',
    liveSearch:true
  });

    $('.change-user-status-form select[name="status"]').on('change', (event) =>  {

        const target = event.currentTarget;
        const userId = $(target).data('id');
        const userName = $(target).data('name');
        const selectedValue = $(target).val();

        bootbox.confirm({
            title: '<h4 class="alert alert-warning text-center"> <i class="fas fa-exclamation-triangle"></i> ' + translate('changeUserStatusHeader') + '&nbsp;<i class="fas fa-exclamation-triangle"></i></h4>',
            size: 'large',
            message: '<p>' + translate('changeUserStatus') +  '</p><p class="text-warning">'+ userName + '</p><p class="text-warning">'+ selectedValue +'</p>',
            buttons: {
                confirm: {
                    label:'<i class="fas fa-check"></i>',
                    className:'btn-danger'
                },
                cancel: {
                    label:'<i class="fas fa-times"></i>',
                    className: 'btn-success'
                }
            },
            callback:(result) => {
                if(result){
                    $('#change-user-status-' + userId).submit();
                }
            }
        });
    });

  $('.toggleRoleBtn').on('click', (event) => {
    const target = event.currentTarget;
    const src = $(target).data('src');
    const targetElement = $(target).data('target');
    $('#' + targetElement).append($('#' + src).html());
    $('#' + src).empty();

    const currentArrow = $(target).text();
    $(target)
      .data('src', targetElement)
      .data('target', src)
      .text(currentArrow.indexOf('>') > -1 ? '<' : '>');

  });
  $('.addRoleBtn, .removeRoleBtn').on('click',(event)=>{
    const target = event.currentTarget;
    const src = $(target).data('src');
    const targetElement = $(target).data('target');
    $('#' + targetElement).append($('#' + src).html());
    $('#' + src).empty();
  });
  $('.fa-angle-double-right').on('click', function() {
    $(this).toggleClass('fa-rotate-90');
  });
  $('#updateUserData').on('click', (event) => {
    $('.permissiontable').each((index, elem) => {
      let section = $(elem).data('section');
      let unassignedRoles = [];
      $(elem).find('.unassignedRoles').find('div').each((i,role)=>{
        let value = $(role).data("value");
        unassignedRoles.push(value);
      });
      $('input[name="role-unassigned-section-' + section + '"]').val(unassignedRoles);

      let assignedRoles = [];
      $(elem).find('.assignedRoles').find('div').each((i,role)=>{
        let value = $(role).data("value");
        assignedRoles.push(value);
      });
      $('input[name="role-assigned-section-' + section + '"]').val(assignedRoles);
    });
  });
});
