import {Guid} from "guid-typescript";
import "bootstrap/js/dist/toast"
import "popper.js"

export function createMessage(header: String, message: String, bgClass: String) {
  let guid = Guid.create();
  let uidString = guid.toString();

  $('body').append(
    `
 <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="${uidString}" data-autohide="false" style="position: fixed; top: 50%; right: 50%;">
 <div class="toast-header text-primary ${bgClass}">
    <strong class="mr-auto"> ${header} </strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
     </button>
 </div>
 <div class="toast-body text-primary">${message}</div>
 </div>
 `);
  $('#' + uidString).toast('show');
  return uidString;
}
