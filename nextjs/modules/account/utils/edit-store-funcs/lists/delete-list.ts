import { AccountListsStore } from "@modules/account/ts/types/store.type";

export const deleteList = (
  state: AccountListsStore,
  productListId: number
): AccountListsStore => ({
  ...state,
  lists: state.lists?.filter((list) => list.productListId != productListId),
  listView: null,
});
