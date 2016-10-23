///<reference path="../typings/index.d.ts"/>

class Question {

    private questionDiv;

    constructor(element: string) {
        this.questionDiv = $(element).closest('[name^=question]');
    }
}