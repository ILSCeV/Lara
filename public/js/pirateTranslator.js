/**
 * Created by fabian on 31.03.17.
 */

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

$(window).load(
    function () {

        var arrifier = function (str) {
            var regex = /ar/g;
            return str.replace(regex,'ARRR');
        };

        var doTranslations = function (index, elem) {
            var oldText = $(elem).text().trim();
            var translation = translate(oldText);
            if(translation.indexOf('Translation necessary:') === -1){
                var newText = $(elem).html().replaceAll(oldText, translation);
                var aRRRRifiedText = arrifier(newText);
                $(elem).html(aRRRRifiedText);
            }
        };

        $('div.cal-event a').each(doTranslations);
        $('span.name').each(doTranslations);
        $('div.panelEventView .panel-title ').each(doTranslations);
    }
);