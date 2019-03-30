import {translate} from "./Translate"

function replaceAll(string, search, replacement) {
    return string.split(search).join(replacement);
}

declare var enviroment: any;

let arrifier = function (str) {
  let regex = new RegExp('ar', 'i');
  return str.replace(regex, 'ARRR');
};

let doTranslations = function (index, elem) {
  let oldText = $(elem).text().trim();
  let translation = translate(oldText);
  let newText = oldText;
  if (translation.indexOf('Translation necessary:') === -1) {
    newText = replaceAll($(elem).html(), oldText, translation);
  }
  let aRRRRifiedText = arrifier(newText);
  $(elem).html(aRRRRifiedText);
};

export const arrifieEvents = () => {
  $('div.cal-event a:nth-of-type(2)').each(doTranslations);
  $('span.name').each(doTranslations);
  $('div.panelEventView .card-title ').each(doTranslations);
};

export const isPirateLanguageActive = () => {
  return typeof localStorage !== "undefined" && (localStorage.getItem("language") || 'de') === 'pirate';
};

if (isPirateLanguageActive()) {
  $(window).on('load',
    function () {

      $('section.container-fluid.containerNopadding').first().css('opacity', 0.9);

      let navLogoField = $('#nav-logo-field');
      let srcfield = navLogoField.attr('src');

      var logonSuffix;
      if (enviroment === "development") {
        logonSuffix = "dev";
      } else if (enviroment === "berta") {
        logonSuffix = "berta"
      } else {
        logonSuffix = "prod"
      }

      navLogoField.attr('src', srcfield.replace(/logo-.*\.png/g, 'logo-' + logonSuffix + '-pirate.png'));
      navLogoField.attr('alt', 'LARRRRA');

      arrifieEvents();

    }
  );
}
