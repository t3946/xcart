const emailReducer = (state = {}, action) => {
  switch (action.type) {
    case "GET_PAGE":
      return { ...state, loading: true };
    case "GET_ITEMS_COUNT":
      return { ...state, loading: true };
    case "SET_PAGE":
      return {
        ...state,
        items: action.json,
        loading: false,
        page: action.page,
      };
    case "SET_ITEMS_COUNT":
      return { ...state, loading: false, itemsCount: action.itemsCount };
    case "SET_SEARCH_OPTIONS":
      return {
        ...state,
        searchOptions: action.searchOptions,
      };
    default:
      return state;
  }
};
export default emailReducer;
