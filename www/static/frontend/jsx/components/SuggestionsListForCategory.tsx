import renderToStringr from "preact-render-to-string";
import React from "react";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";

import Styles from "@client/jsx/components/SuggestionsListForCategory.module.scss";

const SuggestionsListForCategory: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  function renderListItem(item, regExp) {
    const href = item.link;
    const label = item.name.replace(regExp, "<b>$1</b>");

    return (
      <a
        className={Styles.link}
        href={href}
        dangerouslySetInnerHTML={{ __html: label }}
      />
    );
  }

  return <SuggestionsList suggestion={props} renderListItem={renderListItem} />;
};

export default SuggestionsListForCategory;
