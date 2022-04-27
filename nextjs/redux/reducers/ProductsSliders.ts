import { AnyAction } from "redux";

const initialValue = {
  featured: {
    items: [],
    pagination: {
      current: 1,
      total: null,
    },
  },
};

const ProductsSliders = (
  store: Record<any, any> = initialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "PRODUCT_SLIDER_FEATURED_ADD_ITEMS":
      store.featured.items.push(...action.items);

      return { ...store };

    case "PRODUCT_SLIDER_FEATURED_SET_PAGINATION":
      store.featured.pagination = {
        ...store.pagination,
        ...action.pagination,
      };

      return { ...store };

    default:
      return store;
  }
};

export default ProductsSliders;
