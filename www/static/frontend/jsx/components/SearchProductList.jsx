import {h, render, Component} from "preact";
import _ from "lodash";
import renderToStringr from 'preact-render-to-string';
import SearchSuggestionsList from './SearchSuggestionsList'

export default class SearchProductList extends SearchSuggestionsList {


    initState(props) {

        console.log('SearchProductList', props);
        let regExp = new RegExp("(" + props.search.split(' ').join('|') + ")", "gi");
        let suggestions = _.map(props.suggestions, (item, n) => {

            // экранирует спецсимволы если они были в строке
            return {
                value: renderToStringr(item.name),
                html: this.renderListItem(item, regExp)
            };
        });

        this.state = {
            'search': renderToStringr(props.search),
            // Можно безопасно выводить html
            'list': suggestions
        };
    }

    renderListItem(item, regExp){
        let name = renderToStringr(item.name);
        let src = item.image;
        let href = item.link;
        let label = name.replace(regExp, "<b>$1</b>");

        return renderToStringr (
            <a href={href}>
                <div className="icon">
                    <img src={src} alt={name}/>
                </div>
                <div className="label" dangerouslySetInnerHTML={{__html: label}}></div>
            </a>
        );
    }

    render(props, state) {
        return (<div className="products">
            <div className="productsTitle">{props.title}</div>
            <ul>
                {this.items(props)}
            </ul>
        </div>);
    }

}