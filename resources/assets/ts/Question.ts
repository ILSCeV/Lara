///<reference path="_references"/>

class Question {

    private questionDiv: JQuery;

    constructor(element: string) {
        this.questionDiv = $(element).closest('[name^=question]');
    }
}