/**
 * Created by fabian on 31.03.17.
 */
$(window).load(
    function () {
        console.log($('div.cal-event a'));

        $('div.cal-event a').each(function (index, elem) {
            var translation = translate($(elem).text().trim());
            console.log(translation);
            if(translation.indexOf('Translation necessary:') === -1){
                $(elem).text(translation);
            }
        });
    }
);