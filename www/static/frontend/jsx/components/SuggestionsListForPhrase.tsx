import React from "react";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";

const SuggestionsListForPhrase: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  const { suggestions } = props;
  function renderListItem(item, regExp) {
    return (
      <span
        dangerouslySetInnerHTML={{
          __html: item.name.replace(regExp, "<b>$1</b>"),
        }}
      />
    );
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
