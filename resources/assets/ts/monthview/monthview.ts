import {showAllActiveSections} from "../common/filters";
import {scrollToCurrentWeek} from "../common/scrolling"
import {arrifieEvents, isPirateLanguageActive} from "../pirateTranslator";
import { Tooltip } from "bootstrap";

declare var year: any;
declare var month: any;
declare var isAuthenticated: any;

function addProgressBars(){
  document.querySelectorAll("div[data-total-shifts]").forEach( (div : HTMLDivElement) => {

    // Absolute values
    const total = parseInt(div.dataset.totalShifts);
    if(! (total>0)) return; // No shifts, don't display a progress bar
    const free = parseInt(div.dataset.emptyShifts);
    const freeOptional = parseInt(div.dataset.optEmptyShifts);
    const taken = total - free - freeOptional;

    // Percentages 0-100
    const takenPerc = 100 * taken/total;
    const freePerc = 100 * free/total;
    const freeOptPerc = 100 * freeOptional/total;
    
    const progress = `
    <div class="progress-stacked" style="height:6px;  box-shadow: 0 0 1px 1px #ffffff; margin-bottom:3px">
      <div class="progress" role="progressbar" title="${taken}" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Taken shifts" aria-valuenow="${takenPerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${takenPerc}%">
        <div class="progress-bar bg-success"></div>
      </div>
      <div class="progress" role="progressbar" title="${free}" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Free" aria-valuenow="${freePerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${freePerc}%">
        <div class="progress-bar bg-danger" ></div>
      </div>
      <div class="progress" role="progressbar" title="${freeOptional}"  data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Free (opt)" aria-valuenow="${freeOptPerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${freeOptPerc}%">
        <div class="progress-bar bg-warning"></div>
      </div>
    </div>`;
    const progressBar = document.createElement("div");
    progressBar.innerHTML = progress;

    // Enable tooltips
    progressBar.querySelectorAll('div[data-bs-toggle="tooltip"]').forEach(t=>{
     //  new Tooltip(t)   // When this gets uncommented, the dropdown in the navbar stop working
    })
    div.prepend(progressBar);
  });
}

$(window).on({
  load: () => {
    if (("" + month).length < 2) {
      month = "0" + month;
    }

    $.ajax({
      url: "/monthViewTable/" + year + "/" + month,

      data: {
        // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
        "_token": $(this).find('input[name=_token]').val(),
      },

      dataType: 'json',

      success(data: any, textStatus: string, jqXHR: JQueryXHR): any {
        $('#monthTableContainer').html(data.data);
        //initFilters();
        showAllActiveSections();
        if (isPirateLanguageActive()) {
          arrifieEvents();
        }
        addProgressBars();
        scrollToCurrentWeek();

        if (isAuthenticated) {
          $.ajax({
            url: "/monthViewShifts/" + year + "/" + month,

            data: {
              // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
              "_token": $(this).find('input[name=_token]').val(),
            },

            dataType: 'json',

            success(data: Array, textStatus: string, jqXHR: JQueryXHR): any {

              //answer is a array of ids
              var selector = '';
              var first = true;
              data.forEach((value) => {
                if (!first) {
                  selector += ', ';
                }
                selector += '#cal-event-' + value.id;
                first = false;
              });

              $(selector).addClass('cal-month-my-event');
            }
          });
        }
      }
    });
  }
});
