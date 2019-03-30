import {showAllActiveSections} from "../common/filters";
import {scrollToCurrentWeek} from "../common/scrolling"
import {arrifieEvents, isPirateLanguageActive} from "../pirateTranslator";

declare var year: any;
declare var month: any;
declare var isAuthenticated: any;

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
