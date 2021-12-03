import React from "react";
import map from "lodash/map";
import ReactDOMServer from "react-dom/server";
import cn from "classnames";
import Styles from "@modules/old-components/SuggestionsListForPhrase.module.scss";

interface IProps {
  searchString: string;
  suggestions: [];
  title: string;
  parent: any;
}

const SuggestionsListForPhrase: React.FC<IProps> = function (props: IProps) {
  const { searchString, suggestions, title, parent } = props;

  function initState() {
    const re = new RegExp("(" + searchString.split(" ").join("|") + ")", "gi");
    const suggestionsTemplates = map(suggestions, (item, n) => {
      // экранирует спецсимволы если они были в строке
      const string = ReactDOMServer.renderToString(item + "");
      return {
        value: string,
        html: string.replace(re, "<b>$1</b>"),
      };
    });

    return suggestionsTemplates;
  }

  function chooseItem(item) {
    const detail = {
      item: item,
    };

    const event = new CustomEvent("components.search-suggestions-list.click", {
      detail: detail,
    });

    // todo: non-translatable
    // this.props.parent.dispatchEvent(event);
  }

  function items() {
    // Добавляет в состояние найденные строки, шифрует экранированы
    const list = initState(props);

    // Строка, выведенная в dangerouslySetInnerHTML предварительно экранирована
    return map(list, (item, index) => {
      const classes = ["item" + index, "px-3", Styles.item];

      return (
        <li
          onClick={(e) => {
            chooseItem(item.value);
          }}
          dangerouslySetInnerHTML={{ __html: item.html }}
          className={cn(classes)}
          key={index}
        />
      );
    });
  }

  return (
    <div className="phrase suggestions">
      <div className={cn(Styles.suggestionsTitle, "text-end")}>{title}</div>
      <ul className="list-unstyled m-0 pb-2">{items()}</ul>
    </div>
  );
};

export default SuggestionsListForPhrase;
