import { h, render, Component } from "preact";
import map from "lodash/map";
import renderToStringr from "preact-render-to-string";
import SuggestionsListForPhrase from "./SuggestionsListForPhrase";

export default class SuggestionsListForProduct extends SuggestionsListForPhrase {
  initState(props) {
    let regExp = new RegExp(
      "(" + props.search.split(" ").join("|") + ")",
      "gi"
    );
    let suggestions = map(props.suggestions, (item, n) => {
      return {
        // экранирует спецсимволы если они были в строке
        value: renderToStringr(item.name),
        html: this.renderListItem(item, regExp),
      };
    });

    this.state = {
      search: renderToStringr(props.search),
      // Можно безопасно выводить html
      list: suggestions,
    };
  }

  renderListItem(item, regExp) {
    let name = renderToStringr(item.name);
    let src = item.image;
    let href = item.link;
    let label = name.replace(regExp, "<b>$1</b>");

    let icon = h(
      "span",
      {
        className: "icon",
        style: src ? 'background-image: url("' + src + '")' : "",
      },
      h(
        "span",
        {
          className: "show-for-sr",
        },
        name
      )
    );

    return renderToStringr(
      <a href={href}>
        {icon}
        <span
          className="label"
          dangerouslySetInnerHTML={{ __html: label }}
        ></span>
      </a>
    );
  }

  items(props) {
    // Добавляет в состояние найденные строки, шифрует экранированы
    this.initState(props);

    // Строка, выведенная в dangerouslySetInnerHTML предварительно экранирована
    return map(this.state.list, (item, n) => (
      <li
        className={"item" + n}
        dangerouslySetInnerHTML={{ __html: item.html }}
      ></li>
    ));
  }

  render(props, state) {
    return (
      <div className="product suggestions">
        <div className="suggestionsTitle">{props.title}</div>
        <ul>{this.items(props)}</ul>
      </div>
    );
  }
}
