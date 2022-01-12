import { h } from "preact";
import renderToStringr from "preact-render-to-string";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";
import SuggestionsListStyles from "@client/jsx/components/SuggestionsList.module.scss";
import Styles from "@client/jsx/components/SuggestionsListForProduct.module.scss";
import React from "react";

const SuggestionsListForProduct: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  function renderListItem(
    item: { image: string; link: string; name: string },
    regExp
  ) {
    const src = item.image;
    const href = item.link;
    const label = item.name.replace(regExp, "<b>$1</b>");

    const icon = h(
      "span",
      {
        className: Styles.icon,
        style: src ? 'background-image: url("' + src + '")' : "",
      },
      h(
        "span",
        {
          className: "show-for-sr",
        },
        item.name
      )
    );

    return (
      <a className={Styles.link} href={href}>
        {icon}
        <span
          className={Styles.label}
          dangerouslySetInnerHTML={{ __html: label }}
        ></span>
      </a>
    );
  }

  return (
    <SuggestionsList
      suggestion={props}
      classes={[
        SuggestionsListStyles.suggestion_d_none,
        Styles.suggestion_product,
      ]}
      renderListItem={renderListItem}
    />
  );
};

export default SuggestionsListForProduct;
