export const getSuggestionsAction = (payload: Record<any, any>): any => ({
  type: "GET_SUGGESTIONS",
  payload,
});

export const setSuggestionsAction = (
  payload: {
    category_suggestions: any[];
    phrase_suggestions: any[];
    product_suggestions: string | null;
  } | null
): any => ({
  type: "SET_SUGGESTIONS",
  payload,
});
