import React from "react";
import renderToStringr from "preact-render-to-string";
import cn from "classnames";

import Styles from "@client/jsx/components/SuggestionsList.module.scss";

export interface ISuggestion {
  searchString: string;
  suggestions: any[];
  title: string;
}

interface IProps {
  suggestion: ISuggestion;
  classes?: any;
  renderListItem: any;
  chooseItem?: any;
}

const SuggestionsList: React.FC<IProps> = function (props: IProps) {
  const {
    suggestion: { searchString, suggestions, title, type },
    renderListItem,
    chooseItem,
  } = props;

  function initState() {
    const re = new RegExp("(" + searchString.split(" ").join("|") + ")", "gi");
    const suggestionsTemplates = suggestions.map((item, n) => {
      // экранирует спецсимволы если они были в строке
      console.log("item.name", item.name);
      return {
        value: item.name,
        html: renderListItem(item, re),
      };
    });

    return suggestionsTemplates;
  }

  function items() {
    // Добавляет в состояние найденные строки, шифрует экранированы
    const list = initState();

    // Строка, выведенная в dangerouslySetInnerHTML предварительно экранирована
    return list.map((item, index) => {
      const classes = [
        "px-3",
        "m-0",
        Styles.item,
        props.classes,
        Styles["item" + index],
      ];

      return (
        <li
          onClick={
            chooseItem
              ? (e) => {
                  chooseItem(item.value);
                }
              : undefined
          }
          className={cn(classes)}
          key={index}
        >
          {item.html}
        </li>
      );
    });
  }

  return (
    <div className={Styles.suggestion}>
      <div className={cn(Styles.suggestionsTitle, "text-end")}>{title}</div>
      <ul className={cn("list-unstyled", "m-0", "pb-2", Styles.list)}>
        {items()}
      </ul>
    </div>
  );
};

export default SuggestionsList;
