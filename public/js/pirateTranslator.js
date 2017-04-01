/**
 * Created by fabian on 31.03.17.
 */
if ((localStorage.getItem( "language" ) || 'pirate')  === "pirate") {
    String.prototype.replaceAll = function (search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };

    $(window).load(
        function () {
            var navLogoField = $('#nav-logo-field');
            var srcfield  = navLogoField.attr('src');
            navLogoField.attr('src', srcfield.replace(/logo.png/g,'logo-pirate.png'));
            navLogoField.attr('alt','LARRRRA');
            document.body.style = "background-image:url(" + window.location.protocol + "//" + window.location.host + "/background-pirate.jpg) !important; background-size:initial; background-position:center;";

            var arrifier = function (str) {
                var regex = new RegExp('ar', 'i');
                return str.replace(regex, 'ARRR');
            };

            var doTranslations = function (index, elem) {
                var oldText = $(elem).text().trim();
                var translation = translate(oldText);
                var newText = oldText;
                if (translation.indexOf('Translation necessary:') === -1) {
                    newText = $(elem).html().replaceAll(oldText, translation);
                }
                var aRRRRifiedText = arrifier(newText);
                $(elem).html(aRRRRifiedText);
            };

            $('div.cal-event a:nth-child(2)').each(doTranslations);
            $('span.name').each(doTranslations);
            $('div.panelEventView .panel-title ').each(doTranslations);
        }
    );
}