import { h, render, Component } from "preact";
import map from "lodash/map";
import renderToStringr from "preact-render-to-string";

export default class SuggestionsListForPhrase extends Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.initState(props);
  }

  initState(props) {
    let re = new RegExp("(" + props.search.split(" ").join("|") + ")", "gi");
    let suggestions = map(props.suggestions, (item, n) => {
      // экранирует спецсимволы если они были в строке
      let string = renderToStringr(item + "");
      return {
        value: string,
        html: string.replace(re, "<b>$1</b>"),
      };
    });

    this.state = {
      search: renderToStringr(props.search),
      // Можно безопасно выводить html
      list: suggestions,
    };
  }

  chooseItem(item) {
    let detail = {
      item: item,
    };

    let event = new CustomEvent("components.search-suggestions-list.click", {
      detail: detail,
    });
    this.props.parent.dispatchEvent(event);
  }

  items(props) {
    // Добавляет в состояние найденные строки, шифрует экранированы
    this.initState(props);

    // Строка, выведенная в dangerouslySetInnerHTML предварительно экранирована
    return map(this.state.list, (item, n) => {
      return (
        <li
          onClick={(e) => {
            this.chooseItem(item.value);
          }}
          dangerouslySetInnerHTML={{ __html: item.html }}
          className={"item" + n}
        ></li>
      );
    });
  }

  render(props, state) {
    return (
      <div className="phrase suggestions">
        <div className="suggestionsTitle">{props.title}</div>
        <ul>{this.items(props)}</ul>
      </div>
    );
  }
}
