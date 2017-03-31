/**
 * Created by fabian on 31.03.17.
 */
$(window).load(
    function () {
        var doTranslations = function (index, elem) {
            var translation = translate($(elem).text().trim());
            if(translation.indexOf('Translation necessary:') === -1){
                $(elem).text(translation);
            }
        };

        $('div.cal-event a').each(doTranslations);
        $('span.name').each(doTranslations);
    }
);