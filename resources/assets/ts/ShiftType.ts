
import {translate} from "./Translate";
import * as bootbox from "bootbox";

$('.shiftTypeSelector').change((event) => {
  let selectElement = $(event.target);
  let selectedValue = selectElement.val();
  if (selectedValue == -1) {
    return;
  }
  $(event.target).parents('form').submit();
});

$('.shiftTypeReplaceSelector').change((event) => {
  let selectElement = $(event.target);
  let selectedValue = selectElement.val();
  if (selectedValue == -1) {
    return;
  }
  let submitBtn = $(event.target).parents('form');
  let shiftName = $(event.target).parents('form').children('input[name="shiftName"]').val();

  // Initialise modal and show loading icon and message
  bootbox.confirm({
    title: '<h4 class="alert alert-danger text-center">' + translate('deleteTemplate') + '</h4>',
    size: 'large',
    message: '<p>' + translate('replaceShiftTypeConfirmation') + '</p><p class="text-danger">' + shiftName + '</p>',
    buttons: {
      confirm: {
        label: '<span class="fa-solid  fa-check" ></span>' + translate('replaceAll'),
        className: 'btn-success'
      },
      cancel: {
        label: '<span class="fa-solid  fa-window-close" ></span>',
        className: 'btn-secondary'
      }
    },
    callback: (result) => {
      if (result) {
        submitBtn.submit();
      }
    }
  });
});
