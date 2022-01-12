export const toggleSearchIsVisibleAction = (): any => ({
  type: "MOBILE_SEARCH_TOGGLE_VISIBLE",
});

export const setSearchIsVisibleAction = (isVisible: any): any => ({
  type: "MOBILE_SEARCH_SET_VISIBLE",
  isVisible: isVisible,
});
