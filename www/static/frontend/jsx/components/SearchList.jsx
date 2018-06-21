import {h, render, Component} from "preact";
import _ from "lodash";
import renderToStringr from 'preact-render-to-string';
import SearchSuggestionsList from './SearchSuggestionsList'
import SearchProductList from './SearchProductList';

export default class SearchList extends Component {

    constructor(props) {
        super(props);
    }

    render(props, state) {
        return (<div className="found">
            <SearchSuggestionsList  suggestions={props.suggestions.phrase_suggetions} search={props.search}
                                    title="Search suggestions" parent={props.parent} />
            <SearchProductList  suggestions={props.suggestions.product_suggetions} search={props.search}
                                title="Products" parent={props.parent} />
        </div>);
    }

}