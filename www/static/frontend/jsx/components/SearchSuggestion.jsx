import {h, render} from 'preact';
import storeApp from '../stores/StoreApp';
import SearchSuggestionsList from "./SearchSuggestionsList";
//import _ from "lodash";

export default class SearchSuggestion {
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
        this.elements['parent'] = this.elements['search'].parent();
        this.elements['clear'] = this.elements['parent'].find('.button-clear');
        this.elements['container'] = $('<dev />').addClass('suggestion-container');
        this._bind();
    }

    checkValue(str) {
        return str && str.length >= 3;
    }

    show() {
        this.elements['parent'].addClass('suggestion-active');
        this.elements['parent'].append(this.elements['container']);
        this.storeSearchShow();

        $(document).on('click.close_search_suggestion', event => {
            let target = $(event.target);
            if (!target.hasClass('search-form-container') && target.parents('.search-form-container').length <= 0) {
                this.hide();
                $(document).off('click.close_search_suggestion');
            }
        });
    }

    hide() {

        if (!this.elements['parent'].hasClass('suggestion-active')) {
            return;
        }

        this.elements['parent'].removeClass('suggestion-active');
        this.elements['container'].detach();
        this.storeSearchHide();
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
                    console.log(data);
                    if (currentNumber === this.suggestionNumber && data.suggests && data.suggests.length > 0) {
                        this.setSuggestion(data.suggests, data.q)
                    }
                }
            });
        }, this.timeout);
    }

    setSuggestion(data, search) {

        this.show();
        let title = 'Search suggestions';
        //suggestion-container
        render(<SearchSuggestionsList suggestions={data} search={search} title={title}
                                      parent={this.elements['parent'][0]}/>,
            this.elements['container'][0], this.elements['container'][0].firstChild);

        if (data) {
            this.show();
            return;
        }

        this.hide();

    }


    _bind() {

        this.elements['parent'][0].addEventListener('components.search-suggestions-list.click', (e) => {
            let detail = e.detail.item.replace(/[^a-zA-Z\- ]/g, "");

            this.elements['search'].val(detail);
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
                //this.getSuggestions(value);
                this.show();
            }
        });

        this.elements['clear'].on('click', (e) => {
            this.elements['search'].val('');
            this.hide();
            this.elements['clear'].removeClass('active');
        });

    }

    storeSearchHide() {
        storeApp.dispatch({
            type: 'SET', data: {
                frontend: {
                    darkness: false,
                    header: {
                        active: null
                    }
                }
            }
        });
    }

    storeSearchShow() {
        storeApp.dispatch({
            type: 'SET', data: {
                frontend: {
                    darkness: true,
                    header: {
                        active: 'search'
                    }
                }
            }
        });
    }
}
