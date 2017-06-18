/**
 * Created by fabian on 31.03.17.
 */

interface String {
    replaceAll: (search: string, replacement: string) => string
}
String.prototype.replaceAll = function (search, replacement) {
    let target = this;
    return target.split(search).join(replacement);
};

declare var enviroment: any;

if ( typeof localStorage !== "undefined" && (localStorage.getItem("language") || 'de') === 'pirate') {
    $(window).on('load',
        function () {
            let navLogoField = $('#nav-logo-field');
            let srcfield  = navLogoField.attr('src');

            var logonSuffix;
            if(enviroment === "development"){
                logonSuffix = "dev";
            } else if(enviroment === "berta"){
                logonSuffix = "berta"
            } else {
                logonSuffix = "prod"
            }

            navLogoField.attr('src', srcfield.replace(/logo-.*\.png/g,'logo-' + logonSuffix + '-pirate.png'));
            navLogoField.attr('alt','LARRRRA');

            let arrifier = function (str) {
                let regex = new RegExp('ar', 'i');
                return str.replace(regex, 'ARRR');
            };

            let doTranslations = function (index, elem) {
                let oldText = $(elem).text().trim();
                let translation = translate(oldText);
                let newText = oldText;
                if (translation.indexOf('Translation necessary:') === -1) {
                    newText = $(elem).html().replaceAll(oldText, translation);
                }
                let aRRRRifiedText = arrifier(newText);
                $(elem).html(aRRRRifiedText);
            };

            $('div.cal-event a:nth-child(2)').each(doTranslations);
            $('span.name').each(doTranslations);
            $('div.panelEventView .panel-title ').each(doTranslations);
        }
    );
}
