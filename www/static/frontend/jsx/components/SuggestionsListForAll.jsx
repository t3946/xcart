import {h, render, Component} from "preact";
import SuggestionsListForPhrase from './SuggestionsListForPhrase'
import SuggestionsListForProduct from './SuggestionsListForProduct';
import SuggestionsListForCategory from './SuggestionsListForCategory';

export default class SuggestionsListForAll extends Component {

    constructor(props) {
        super(props);
    }

    renderPhrase(props) {
        if (props.suggestions.phrase_suggestions && props.suggestions.phrase_suggestions.length > 0) {
            console.log('renderPhrase');
            return <SuggestionsListForPhrase suggestions={props.suggestions.phrase_suggestions} search={props.search}
                                             title="Search suggestions" parent={props.parent}/>
        }
    }

    renderCategory(props) {
        if (props.suggestions.category_suggestions && props.suggestions.category_suggestions.length > 0) {
            console.log('renderCategory');
            return <SuggestionsListForCategory suggestions={props.suggestions.category_suggestions} search={props.search}
                                               title="Categories" parent={props.parent}/>
        }
    }

    renderProduct(props) {
        if (props.suggestions.product_suggestions && props.suggestions.product_suggestions.length > 0) {
            console.log('renderProduct');
            return <SuggestionsListForProduct suggestions={props.suggestions.product_suggestions} search={props.search}
                                              title="Products" parent={props.parent}/>
        }
    }

    render(props, state) {
        return (<div className="found">
            {this.renderPhrase(props)}
            {this.renderCategory(props)}
            {this.renderProduct(props)}
        </div>);
    }

}