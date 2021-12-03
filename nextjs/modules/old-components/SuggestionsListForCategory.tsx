import map from "lodash/map";
import renderToStringr from "preact-render-to-string";

const SuggestionsListForCategory: React.FC = function () {
  return "SuggestionsListForCategory";
};

export default SuggestionsListForCategory;

class SuggestionsListForCategory_OLD {
  initState(props) {
    const regExp = new RegExp(
      "(" + props.search.split(" ").join("|") + ")",
      "gi"
    );
    const suggestions = map(props.suggestions, (item, n) => {
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
    const name = renderToStringr(item.name);
    const href = item.link;
    const label = name.replace(regExp, "<b>$1</b>");

    return renderToStringr(
      <a href={href} dangerouslySetInnerHTML={{ __html: label }}></a>
    );
  }

  items(props) {
    // Добавляет в состояние найденные строки, шифрует экранированы
    this.initState(props);
    // Строка, выведенная в dangerouslySetInnerHTML предварительно экранирована
    return map(this.state.list, (item, n) => (
      <li
        dangerouslySetInnerHTML={{ __html: item.html }}
        className={"item" + n}
      ></li>
    ));
  }

  render(props, state) {
    return (
      <div className="category suggestions">
        <div className="suggestionsTitle">{props.title}</div>
        <ul>{this.items(props)}</ul>
      </div>
    );
  }
}
