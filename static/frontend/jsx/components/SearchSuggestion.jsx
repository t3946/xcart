
export default class SearchSuggestion
{
    constructor(elements = ".search-form-container .search") {
        this.elements = {};
        this.suggestions = {};
        this.suggestionNumber = 0;
        this.timer = null;
        this.timeout = 400;

        this.init(elements);
    }

    init(elements) {

        this.elements['search'] = $(elements);

        this._bind();
    }

    show() {

    }

    hide() {

    }

    getSuggestions(str) {
        if (str && str.length >= 3) {
            this.suggestionNumber++;

            let currentNumber = this.suggestionNumber;

            clearTimeout(this.timer);
            this.timer = setTimeout(()=>{
                $.ajax(this.elements['search'].data('suggestion-url'), {
                    'success' : (data)=>{
                        if (currentNumber === this.suggestionNumber) {

                            console.log(data);

                        }
                    }
                });
            }, this.timeout);
        }
    }


    _bind() {

        this.elements['search'].on('change keyup', (e)=>{
            let $target = this.elements['search'];

            this.getSuggestions($target.val());
        });

        this.elements['search'].on('click', (e)=>{
            console.log(e);
            this.show();
        });

        this.elements['search'].on('blur', (e)=>{
            console.log(e);
            this.hide();
        });
    }
}
