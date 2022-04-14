import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const moveProductList = (
  state: AccountListsStore,
  fromListId: number,
  toListId: number,
  productId: number
): AccountListsStore => {
  const productMove = state.currentList?.items.find(
    (product) => product.list_item_id === productId
  );

  return {
    ...state,
    lists: state.lists?.map((list) => {
      if (list.product_list_id === toListId) {
        list.items = [...list.items, productMove];
      } else if (list.product_list_id === fromListId) {
        list.items = list.items.filter(
          (product) => product.list_item_id !== productId
        );
      }

      return list;
    }),
    currentList: {
      ...state.currentList,
      items: state.currentList?.items.map((product) => {
        if (product.list_item_id === productId) {
          product.typeAction = {
            type: "move",
            productName: product.product?.product || product.idea?.name,
            toListId: state.lists?.find(
              (list) => list.product_list_id === toListId
            )?.cacheUrl,
            listName: state.lists?.find(
              (list) => list.product_list_id === toListId
            )?.name,
          };
        }

        return product;
      }),
    },
  };
};
