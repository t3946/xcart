import { AccountListsStore } from "@modules/account/ts/types/store.type";
import {
  ListIdeaInfo,
  ListItem,
  ListProductInfo,
} from "@modules/account/ts/types/list.type";

export const addProductToList = (
  state: AccountListsStore,
  productListId: number,
  product: ListItem
): AccountListsStore => {
  const base: AccountListsStore = {
    lists: state.lists?.map((list) => {
      if (productListId == list.productListId) {
        list.products = [...list.products, product];
      }
      return list;
    }),
    loading: false,
    listView: null,
  };
  if (state.listView) {
    return {
      ...base,
      listView: {
        ...state.listView,
        products: [...state.listView.products, product],
      },
    };
  }
  return base;
};
