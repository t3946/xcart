import renderToStringr from "preact-render-to-string";
import React from "react";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";

import Styles from "@modules/old-components/SuggestionsListForCategory.module.scss";

const SuggestionsListForCategory: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  function renderListItem(item, regExp) {
    const name = renderToStringr(item.name);
    const href = item.link;
    const label = name.replace(regExp, "<b>$1</b>");

    return renderToStringr(
      <a
        className={Styles.link}
        href={href}
        dangerouslySetInnerHTML={{ __html: label }}
      >
      </a>
    );
  }

  return (
    <SuggestionsList
      suggestion={props}
      classes={Styles.suggestion_category}
      renderListItem={renderListItem}
    />
  );
};

export default SuggestionsListForCategory;
