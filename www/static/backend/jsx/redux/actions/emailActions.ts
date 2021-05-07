export const getPage = (page) => ({
  type: "GET_PAGE",
  page: page,
});

export const getItemsCount = () => ({
  type: "GET_ITEMS_COUNT",
});

export const setSearchOptions = (title = undefined) => ({
  type: "SET_SEARCH_OPTIONS",
  searchOptions: {
    title: title,
  },
});
