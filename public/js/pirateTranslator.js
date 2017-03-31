/**
 * Created by fabian on 31.03.17.
 */

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

$(window).load(
    function () {
        var doTranslations = function (index, elem) {
            var oldText = $(elem).text().trim();
            var translation = translate(oldText);
            if(translation.indexOf('Translation necessary:') === -1){
                $(elem).html($(elem).html().replaceAll(oldText, translation));
            }
        };

        $('div.cal-event a').each(doTranslations);
        $('span.name').each(doTranslations);
        $('div.panelEventView .panel-title ').each(doTranslations);
    }
);