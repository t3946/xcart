import {h, render, Component} from "preact";
import _ from "lodash";
//import renderToStringr from 'preact-render-to-string';
//import _ from 'lodash';

export default class SearchSuggestionsList extends Component {


    constructor(props) {
        super(props);

        this.props = props;
        this.initState();
    }

    initState() {

        this.state = {
            'visible': true,
            'list' : []
        };
    }

    items(suggestions){
        return _.map(suggestions, (item, n) => {
            return(<li>{item}</li>);
        });
    }

    render(props, state){
        return (<div className="suggestions">
            <div className="suggestionsTitle">{props.title}</div>
                        <ul>
                            {this.items(props.suggestions)}
                        </ul>
        </div>);
    }


    // render(props, state) {
    //
    //     return (
    //         <div className="suggestions">
    //             <div className="suggestionsTitle">Search suggestions</div>
    //             <ul>
    //                 <li>qwqe</li>
    //                 <li>qweqwe</li>
    //                 <li>qweqe</li>
    //                 <li>qweqwe</li>
    //                 <li>qweqwe</li>
    //             </ul>
    //         <div>
    //     );
    //
    // }


}