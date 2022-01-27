import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const moveProductList = (
  state: AccountListsStore,
  fromListId: number,
  toListId: number,
  productId: number
): AccountListsStore => {
  const productMove = state.listView?.products.find(
    (product) => product.productId === productId
  );
  console.log(fromListId, toListId);
  return {
    ...state,
    lists: state.lists?.map((list) => {
      if (list.productListId === toListId) {
        list.products = [...list.products, productMove];
      } else if (list.productListId === fromListId) {
        list.products = list.products.filter(
          (product) => product.productId !== productId
        );
      }
      return list;
    }),
    listView: {
      ...state.listView,
      products: state.listView?.products.filter(
        (product) => product.productId !== productId
      ),
    },
  };
};
