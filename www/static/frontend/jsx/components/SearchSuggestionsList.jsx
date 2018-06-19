import {h, render, Component} from "preact";
import _ from "lodash";
import renderToStringr from 'preact-render-to-string';

export default class SearchSuggestionsList extends Component {


    constructor(props) {
        super(props);
        this.props = props;
        this.initState(props);
    }

    initState(props) {

        let re = new RegExp("(" + props.search.split(' ').join('|') + ")", "gi");
        let suggestions = _.map(props.suggestions, (item, n) => {

            return {
                value: item,
                label: renderToStringr(item.replace(re, "<b>$1</b>"))
            };
        });

        this.state = {
            'search': props.search,
            'list': suggestions
        };
    }

    chooseItem(item) {
        let detail = {
            item: item
        };

        let event = new CustomEvent('components.search-suggestions-list.click', {detail: detail});
        this.props.parent.dispatchEvent(event);
    }

    items(props) {
        this.initState(props);

        return _.map(this.state.list, (item, n) => {
            return (<li onClick={(e) => {
                this.chooseItem(item.value)
            }}>
                {item.label}
            </li>);
        });
    }

    render(props, state) {
        return (<div className="suggestions">
            <div className="suggestionsTitle">{props.title}</div>
            <ul>
                {this.items(props)}
            </ul>
        </div>);
    }


}