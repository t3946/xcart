import { AnyAction } from "redux";

const initialState = null;

interface ISuggestion {
  category_suggestions: any[];
  phrase_suggestions: any[];
  product_suggestions: string | null;
}

const SuggestionReducer = (
  store: ISuggestion | null = initialState,
  action: AnyAction
): ISuggestion | null => {
  switch (action.type) {
    case "SET_SUGGESTIONS":
      if (action.payload) return { ...action.payload };
      return null;
    default:
      return store;
  }
};
export default SuggestionReducer;
