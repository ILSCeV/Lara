import * as bootbox from "bootbox";

import {translate} from "./Translate";

$(() => {
  $("#userOverviewFilter").on("keyup", (event) => {
    let value = $(event.target).val().toLowerCase();
    $(".userOverviewTable tr").each(function (index, elem) {
      $(elem).toggle($(elem).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $('.change-user-status-form > .selectpicker').selectpicker({
    style: 'btn btn-secondary btn-sm',
    liveSearch: true
  });

  $('.editUserFormselectpicker').selectpicker({
    style: 'btn btn-secondary btn-sm',
    liveSearch: true
  });

  $('.change-user-status-form select[name="status"]').on('change', (event) => {

    const target = event.currentTarget;
    const userName = $(target).data('name');
    const selectedValue = $(target).val();

    bootbox.confirm({
      title: '<h4 class="alert alert-warning text-center"> <i class="fas fa-exclamation-triangle"></i> ' + translate('changeUserStatusHeader') + '&nbsp;<i class="fas fa-exclamation-triangle"></i></h4>',
      size: 'large',
      message: '<p>' + translate('changeUserStatus') + '</p><p class="text-warning">' + userName + '</p><p class="text-warning">' + selectedValue + '</p>',
      buttons: {
        confirm: {
          label: '<i class="fas fa-check"></i>',
          className: 'btn-danger'
        },
        cancel: {
          label: '<i class="fas fa-times"></i>',
          className: 'btn-success'
        }
      },
      callback: (result) => {
        if (result) {
          $(target).closest('.change-user-status-form').trigger('submit');
        }
      }
    });
  });

  $('.deleteUserBtn').on({
    click:(e) => {
    e.preventDefault();
      const target = e.currentTarget;
      const userName = $(target).data('name');

      bootbox.confirm({
        title: '<h4 class="alert alert-danger text-center"> <i class="fas fa-exclamation-triangle"></i> ' + translate('deleteUserHeader') + '&nbsp;<i class="fas fa-exclamation-triangle"></i></h4>',
        size: 'large',
        message: '<p>' + translate('deleteUser') + '</p><p class="text-warning">' + userName + '</p>',
        buttons: {
          confirm: {
            label: '<i class="fas fa-check"></i>',
            className: 'btn-danger'
          },
          cancel: {
            label: '<i class="fas fa-times"></i>',
            className: 'btn-success'
          }
        },
        callback: (result) => {
          if (result) {
            $(target).closest('form').trigger('submit');
          }
        }
      });
    }

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
  $('.addRoleBtn, .removeRoleBtn').on('click', (event) => {
    const target = event.currentTarget;
    const src = $(target).data('src');
    const targetElement = $(target).data('target');
    $('#' + targetElement).append($('#' + src).html());
    $('#' + src).empty();
  });
  $('.fa-angle-double-right').on('click', function () {
    $(this).toggleClass('fa-rotate-90');
  });
  $('#updateUserData').on('click', (event) => {
    $('.permissiontable').each((index, elem) => {
      let section = $(elem).data('section');
      let unassignedRoles = [];
      $(elem).find('.unassignedRoles').find('div').each((i, role) => {
        let value = $(role).data("value");
        unassignedRoles.push(value);
      });
      $('input[name="role-unassigned-section-' + section + '"]').val(unassignedRoles);

      let assignedRoles = [];
      $(elem).find('.assignedRoles').find('div').each((i, role) => {
        let value = $(role).data("value");
        assignedRoles.push(value);
      });
      $('input[name="role-assigned-section-' + section + '"]').val(assignedRoles);
    });
  });

  $('.rolefilter').on({
    click: (e) => {
      e.preventDefault();
      let target = e.target;
      $(target).toggleClass('active');
      $('.userRow').addClass('d-none');
      let targetRows: Array = [];
      $('.rolefilter.active').each((index, elem) => {
        targetRows.push($(elem).data('target'));
      });

      targetRows.forEach((value, index) => {
        $('.' + value).removeClass('d-none');
      });
      if (targetRows.length == 0) {
        $('.userRow').removeClass('d-none');
      }
    }
  });
});
