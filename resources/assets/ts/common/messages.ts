import {Guid} from "guid-typescript";
import "bootstrap/js/dist/toast"
import "popper.js"

export function createMessage(header: String, message: String, bgClass: String) {
  let guid = Guid.create();
  let uidString = guid.toString();

  $('body').append(
    `
 <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="${uidString}" data-bs-autohide="false" style="position: fixed; top: 50%; right: 50%;">
 <div class="toast-header ${bgClass}">
    <strong class="me-auto"> ${header} </strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
 </div>
 <div class="toast-body">${message}</div>
 </div>
 `);
  $('#' + uidString).toast('show');
  return uidString;
}
