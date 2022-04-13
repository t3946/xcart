import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const moveProductList = (
  state: AccountListsStore,
  fromListId: number,
  toListId: number,
  productId: number
): AccountListsStore => {
  const productMove = state.listView?.products.find(
    (product) => product.list_item_id === productId
  );
  return {
    ...state,
    lists: state.lists?.map((list) => {
      if (list.productListId === toListId) {
        list.products = [...list.products, productMove];
      } else if (list.productListId === fromListId) {
        list.products = list.products.filter(
          (product) => product.list_item_id !== productId
        );
      }
      return list;
    }),
    listView: {
      ...state.listView,
      products: state.listView?.products.map((product) => {
        if (product.list_item_id === productId) {
          product.typeAction = {
            type: "move",
            productName: product.product.product || product.product.name,
            toListId: state.lists?.find(
              (list) => list.productListId === toListId
            )?.cacheUrl,
            listName: state.lists?.find(
              (list) => list.productListId === toListId
            )?.name,
          };
        }

        return product;
      }),
    },
  };
};
