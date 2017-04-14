interface Translation {
    [key: string]: string;
}

declare const enTranslations: Translation;
declare const deTranslations: Translation;
declare const pirateTranslations: Translation;

type Language = 'de' | 'en' | 'pirate'

function translate(str: string) {
    const language = localStorage["language"];
    const translations = getTranslations(language);
    return translations[str] ? translations[str] : `!! Translation necessary: ${str} in language ${language} !!`;
}

function getTranslations(language: Language) {
    if (language === 'en') {
        return enTranslations;
    }
    if (language === 'de') {
        return deTranslations;
    }
    return pirateTranslations;
}



