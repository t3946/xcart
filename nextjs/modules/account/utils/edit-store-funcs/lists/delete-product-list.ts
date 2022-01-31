import { List } from "@modules/account/ts/types/list.type";

export const deleteProductList = (state: List, list_items_id: number): List => {
  return {
    ...state,
    products: state.products.map((product) => {
      if (product.list_items_id === list_items_id) {
        product.typeAction = {
          type: "delete",
          productName: product.product.product || product.product.name,
        };
      }
      return product;
    }),
  };
};
