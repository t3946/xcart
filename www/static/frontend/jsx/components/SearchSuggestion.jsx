import {h, render} from 'preact';
import storeApp from '../stores/StoreApp';
import SuggestionsListForAll from "./SuggestionsListForAll";
import { checkOff, action } from '../redusers/appHeadReduser';

export default class SearchSuggestion {
    constructor(elements = ".search-form-container .search") {
        this.elements = {};
        this.suggestions = '';
        this.suggestionNumber = 0;
        this.timer = null;
        this.timeout = 400;
        this.suggestionsCreated = false;

        this.init(elements);
    }

    init(elements) {
        this.elements['search'] = $(elements);
        this.elements['parent'] = this.elements['search'].parent();
        this.elements['clear'] = this.elements['parent'].find('.button-clear');
        this.elements['container'] = $('<dev />').addClass('suggestion-container');
        this._bind();
    }

    checkValue(str) {
        return str && str.length >= 3;
    }

    showSuggestionsList() {
        this.elements['parent'].addClass('suggestion-active');
        this.elements['parent'].append(this.elements['container']);
        //this.storeSearchShow();

        $(document).on('click.close_search_suggestion', event => {
            let target = $(event.target);
            if (!target.hasClass('search-form-container') && target.parents('.search-form-container').length <= 0) {
                this.hide();
            }
        });
    }

    hideSuggestionsList() {

        $(document).off('click.close_search_suggestion');
        if (!this.elements['parent'].hasClass('suggestion-active')) {
            return;
        }

        this.elements['parent'].removeClass('suggestion-active');
        this.elements['container'].detach();
    }

    setActive() {
        this.elements['search'].focus();
    }

    setInactive() {
        this.elements['search'].blur();
    }

    getSuggestions(str) {

        if (!this.checkValue(str)) {
            this.hide();
            return;
        }

        this.suggestionNumber++;
        let currentNumber = this.suggestionNumber;

        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            $.ajax(this.elements['search'].data('suggestion-url'), {
                'data': {'q': str},
                'success': (data) => {
                    if (currentNumber === this.suggestionNumber && !!data.suggests) {
                        this.setSuggestion(data.suggests, data.q)
                    }
                }
            });
        }, this.timeout);
    }

    hasPhraseSuggestions(data){
        return data.phrase_suggestions && data.phrase_suggestions.length > 0;
    }

    hasCategorySuggestions(data){
        return data.category_suggestions && data.category_suggestions.length > 0;
    }

    hasProductSuggestions(data){
        return data.product_suggestions && data.product_suggestions.length > 0;
    }

    setSuggestion(data, search) {

        if (!data
            || (!this.hasPhraseSuggestions(data)
                && !this.hasCategorySuggestions(data)
                && !this.hasProductSuggestions(data))) {
            this.hide();
            return;
        }

        //suggestion-container
        render(<SuggestionsListForAll suggestions={data} search={search} parent={this.elements['parent'][0]}/>,
            this.elements['container'][0], this.elements['container'][0].firstChild);
        this.show();
        this.suggestionsCreated = true;
    }


    _bind() {

        this.unsubscribe = storeApp.subscribe(()=>{
            let state = storeApp.getState();

            if (state.frontend) {
                if(state.frontend.header.active == 'search'){
                    this.showSuggestionsList();
                    this.setActive()
                }
                else {
                    this.hideSuggestionsList();
                    if(state.frontend.header.active !== null){
                        this.setInactive()
                    }
                }

            }
        });

        this.elements['parent'][0].addEventListener('components.search-suggestions-list.click', (e) => {
            let detail = e.detail.item.replace(/[^a-zA-Z\- ]/g, "");

            this.elements['search'].val(detail);
            this.elements['parent'].submit();
            this.hide();

        }, {passive: true});

        this.elements['search'].on('keyup', (e) => {
            let $target = this.elements['search'];
            let value = $target.val();

            if (value) {
                this.elements['clear'].addClass('active');
            }
            else {
                this.elements['clear'].removeClass('active');
            }

            this.getSuggestions(value);

        });

        this.elements['search'].on('click', (e) => {
            let value = e.target.value.trim();
            if (this.checkValue(value)) {
                if(this.suggestionsCreated){
                    this.show();
                    return;
                }
                this.getSuggestions(value);
            }
        });

        this.elements['clear'].on('click', (e) => {
            this.elements['search'].val('');
            this.elements['clear'].removeClass('active');
            this.hide();
        });

    }

    hide() {
        checkOff();
    }

    show() {
        action('search');
    }
}
