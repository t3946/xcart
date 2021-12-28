import React from "react";
import { useRouter } from "next/router";
import ReactDOMServer from "react-dom/server";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";

const SuggestionsListForPhrase: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  const { suggestions } = props;
  const router = useRouter();
  function renderListItem(item, regExp) {
    const string = ReactDOMServer.renderToString(item.name);

    return string.replace(regExp, "<b>$1</b>");
  }

  function chooseItem(value: string) {
    document.location.href = `/search?q=${value.replaceAll(" ", "+")}`;
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
