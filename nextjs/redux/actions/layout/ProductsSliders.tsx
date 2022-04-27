export const featuredAddItems = (items: Record<any, any>[]): any => ({
  type: "PRODUCT_SLIDER_FEATURED_ADD_ITEMS",
  items,
});

export const featuredSetPagination = (pagination: Record<any, any>): any => ({
  type: "PRODUCT_SLIDER_FEATURED_SET_PAGINATION",
  pagination,
});

export const load = (payload): any => ({
  type: "PRODUCT_SLIDER_LOAD",
  payload,
});
