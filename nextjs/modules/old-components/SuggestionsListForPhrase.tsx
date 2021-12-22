import React from "react";
import map from "lodash/map";
import ReactDOMServer from "react-dom/server";
import cn from "classnames";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";

const SuggestionsListForPhrase: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  const { suggestions } = props;

  function renderListItem(item, regExp) {
    const string = ReactDOMServer.renderToString(item.name);

    return string.replace(regExp, "<b>$1</b>");
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
  const suggestionList = suggestions.map((item) => {
    return { name: item };
  });
  return (
    <SuggestionsList
      suggestion={{ ...props, suggestions: suggestionList }}
      renderListItem={renderListItem}
      chooseItem={chooseItem}
    />
  );
};

export default SuggestionsListForPhrase;
