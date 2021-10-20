import React from "react";
import { SuggestionList } from "./SuggestionsList";
interface SuggestionsListForAll {
  suggestions: any;
  search: string;
}
export const SuggestionsListForAll: React.FC<SuggestionsListForAll> = ({
  suggestions,
  search,
}) => {
  return (
    <div className="found">
      {suggestions.phrase_suggestions && (
        <SuggestionList
          items={suggestions.phrase_suggestions}
          search={search}
          title="Search suggestions"
          typeList="phrase"
        />
      )}
      {suggestions.category_suggestions && (
        <SuggestionList
          items={suggestions.category_suggestions}
          search={search}
          title="Categories"
          typeList="categories"
        />
      )}
      {suggestions.product_suggestions && (
        <SuggestionList
          items={suggestions.product_suggestions}
          search={search}
          title="Products"
          typeList="product"
        />
      )}
    </div>
  );
};
