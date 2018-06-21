import {h, render} from 'preact';
import storeApp from '../stores/StoreApp';
import SearchList from "./SearchList";
import { checkOff, action } from '../redusers/appHeadReduser';
//import _ from "lodash";

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
        this.elements['search'].blur();
        //this.storeSearchHide();
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

                    if (currentNumber === this.suggestionNumber && data.suggests && data.suggests.length > 0) {
                        this.setSuggestion(data.suggests, data.q)
                    }
                }
            });
        }, this.timeout);
    }

    setSuggestion(data, search) {

        if (!data) {
            this.hide();
            return;
        }

        //suggestion-container
        render(<SearchList suggestions={data} search={search} parent={this.elements['parent'][0]}/>,
            this.elements['container'][0], this.elements['container'][0].firstChild);
        this.show();
        this.suggestionsCreated = true;
    }


    _bind() {

        this.unsubscribe = storeApp.subscribe(()=>{
            let state = storeApp.getState();

            if (state.frontend) {
                //if ( state.frontend.header.mobileSearch)
                if(state.frontend.header.active == 'search'){
                    this.showSuggestionsList();
                }
                else {
                    this.hideSuggestionsList();
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
            $(e.target).focus();
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
            this.hide();
            this.elements['clear'].removeClass('active');
        });

    }

    hide() {
        checkOff();
    }

    show() {
        action('search');
    }
}
