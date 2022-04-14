import { List } from "@modules/account/ts/types/list.type";

export const deleteProductList = (list: List, list_item_id: number): List => {
  console.log("deleteProductList", {list, list_item_id});

  return {
    ...list,
    items: list.items.map((item) => {
      if (item.list_item_id === list_item_id) {
        item.typeAction = {
          type: "delete",
          productName: item.product?.product || item.idea?.name,
        };
      }
      return item;
    }),
  };
};
