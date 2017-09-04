import * as $ from "jquery"

const defaultLanguage = "de";

$(function() {
    localStorage["language"] = localStorage["language"] || defaultLanguage;
});

$('.languageSwitcher').find('a').click(function() {
    var language = $(this).data('language');
    localStorage.setItem('language', language);
});
