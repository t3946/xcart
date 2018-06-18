import {h, render} from 'preact';
import SearchSuggestionsList from "./SearchSuggestionsList";
import _ from "lodash";

export default class SearchSuggestion
{
    constructor(elements = ".search-form-container .search") {
        this.elements = {};
        this.suggestions = '';
        this.suggestionNumber = 0;
        this.timer = null;
        this.timeout = 400;

        this.init(elements);
    }

    init(elements) {
        this.elements['search'] = $(elements);
        this.elements['parent'] = $(this.elements['search'].parent());
        this.elements['container'] = $('<dev />').addClass('suggestion-container');

        this._bind();
    }

    show() {
        this.elements['parent'].addClass('suggestion-active');
        this.elements['parent'].append(this.elements['container']);
    }

    hide() {
        this.elements['parent'].removeClass('suggestion-active');
        this.elements['container'].detach();
    }

    getSuggestions(str) {
        if (str && str.length >= 3) {
            this.suggestionNumber++;

            let currentNumber = this.suggestionNumber;

            clearTimeout(this.timer);
            this.timer = setTimeout(()=>{
                $.ajax(this.elements['search'].data('suggestion-url'), {
                    'data': {'q': str},
                    'success' : (data)=>{
                        if (currentNumber === this.suggestionNumber && data.suggests) {
                            this.setSuggestion(data.suggests, data.q)
                        }
                    }
                });
            }, this.timeout);
        }
    }

    setSuggestion(data, search) {

        var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");

        this.suggestions = _.map(data, (item, n) => {

            return item.replace(re, "<b>$1</b>");
        });
        let title = 'Search suggestions';
        //console.log(this.elements['container']);
        //suggestion-container
        //this.elements['container'].html(this.suggestions);
        //render(<SelectNumberItems number={number} quantity={quantity} max={max} min={min} step={step}/>,window, window.firstChild);
        render(<SearchSuggestionsList suggestions={this.suggestions} title={title} />, this.elements['container'][0], this.elements['container'][0].firstChild);

        if (data) {
            this.show();
        }
        else {
           // this.hide();
        }
    }


    _bind() {

        this.elements['search'].on('change keyup', (e)=>{
            let $target = this.elements['search'];

            this.getSuggestions($target.val());
        });

        this.elements['search'].on('click', (e)=>{
            //console.log(e);
            this.show();
        });

        this.elements['search'].on('blur', (e)=>{
            //console.log(e);
            //this.hide();
        });
    }
}
