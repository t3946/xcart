import React from "react";
import SuggestionsListForPhrase from "@modules/old-components/SuggestionsListForPhrase";
import SuggestionsListForProduct from "@modules/old-components/SuggestionsListForProduct";
import SuggestionsListForCategory from "@modules/old-components/SuggestionsListForCategory";
import classnames from "classnames";
import HatSearchLineStyles from "@modules/account/components/hat/HatSearchLine.module.scss";
import Styles from "@modules/old-components/SuggestionsListForAll.module.scss";

interface IProps {
  suggestions: any;
  searchString: string;
}

const SuggestionsListForAll: React.FC<IProps> = function (props: IProps) {
  const { suggestions, searchString } = props;

  function renderPhrase() {
    if (
      suggestions.phrase_suggestions &&
      suggestions.phrase_suggestions.length > 0
    ) {
      return (
        <SuggestionsListForPhrase
          suggestions={suggestions.phrase_suggestions}
          searchString={searchString}
          title="Search suggestions"
        />
      );
    }
  }

  function renderCategory() {
    if (
      props.suggestions.category_suggestions &&
      props.suggestions.category_suggestions.length > 0
    ) {
      return (
        <SuggestionsListForCategory
          suggestions={suggestions.category_suggestions}
          searchString={searchString}
          title="Categories"
        />
      );
    }
  }

  function renderProduct() {
    if (
      props.suggestions.product_suggestions &&
      props.suggestions.product_suggestions.length > 0
    ) {
      return (
        <SuggestionsListForProduct
          suggestions={suggestions.product_suggestions}
          searchString={searchString}
          title="Products"
        />
      );
    }
  }

  return (
    <div
      className={classnames(
        Styles.mainContainer,
        HatSearchLineStyles.searchFormContainer__suggestion
      )}
    >
      {renderPhrase()}
      {renderCategory()}
      {renderProduct()}
    </div>
  );
};

export default SuggestionsListForAll;
