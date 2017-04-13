class Question {

    private questionDiv: JQuery;

    constructor(element: string) {
        this.questionDiv = $(element).closest('[name^=question]');
    }
}