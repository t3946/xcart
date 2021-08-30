import { AnyAction } from "redux";
import { AccountListsStore } from "@client/modules/account/ts/types/account-store.type";
import { accountListsInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

const accountListReducer = (
  state: AccountListsStore = accountListsInitialValue,
  action: AnyAction
): AccountListsStore => {
  switch (action.type) {
    case "GET_LISTS":
      return { ...state };
    case "SET_LISTS":
      return {
        ...state,
        lists: action.lists,
      };
    case "REORDER_LIST":
      return {
        ...state,
        lists: state.lists.map((e) => {
          if (e.product_list_id === action.product_list_id) {
            return {
              ...e,
              products: action.listIds,
            };
          }
          return e;
        }),
      };
    case "MOVE_PRODUCT":
      console.log(action.toListId);
      return {
        ...state,
        lists: state.lists.map((e) => {
          if (action.fromListId === action.toListId.value) {
            return e;
          }
          if (e.product_list_id === action.fromListId) {
            return {
              ...e,
              products: e.products.filter((product) => {
                if (product.list_items_id !== action.product.list_items_id) {
                  return product;
                }
              }),
            };
          } else if (e.product_list_id === action.toListId.value) {
            return {
              ...e,
              products: e.products.concat(action.product),
            };
          }
          return e;
        }),
      };

    default:
      return state;
  }
};
export default accountListReducer;
