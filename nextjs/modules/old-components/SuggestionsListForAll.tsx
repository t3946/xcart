import React from "react";
import SuggestionsListForPhrase from "@modules/old-components/SuggestionsListForPhrase";
import SuggestionsListForProduct from "@modules/old-components/SuggestionsListForProduct";
import SuggestionsListForCategory from "@modules/old-components/SuggestionsListForCategory";
import classnames from "classnames";
import Styles from "@modules/old-components/SuggestionsListForAll.module.scss";

interface IProps {
  suggestions: any;
  searchString: string;
  parent: any;
  data: {
    category_suggestions: any;
    phrase_suggestions: string[];
    product_suggestions: {
      id: number;
      image: string;
      link: string;
      name: string;
    }[];
  };
}

const SuggestionsListForAll: React.FC<IProps> = function (props: IProps) {
  const { suggestions, searchString, parent } = props;

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
          parent={parent}
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
          suggestions={props.suggestions.category_suggestions}
          search={props.search}
          title="Categories"
          parent={props.parent}
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
          suggestions={props.suggestions.product_suggestions}
          search={props.search}
          title="Products"
          parent={props.parent}
        />
      );
    }
  }

  return (
    <div className={classnames(Styles.mainContainer, props.classes?.container)}>
      {renderPhrase()}
      {/*{renderCategory()}*/}
      {/*{renderProduct()}*/}
    </div>
  );
};

export default SuggestionsListForAll;
