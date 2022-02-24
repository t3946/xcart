import { h } from "preact";
import renderToStringr from "preact-render-to-string";
import SuggestionsList, { ISuggestion } from "./SuggestionsList";
import SuggestionsListStyles from "@modules/old-components/SuggestionsList.module.scss";
import Styles from "@modules/old-components/SuggestionsListForProduct.module.scss";

const SuggestionsListForProduct: React.FC<ISuggestion> = function (
  props: ISuggestion
) {
  function renderListItem(item, regExp) {
    const name = renderToStringr(item.name);
    const src = item.image;
    const href = item.link;
    const label = name.replace(regExp, "<b>$1</b>");

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
        name
      )
    );

    return renderToStringr(
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
