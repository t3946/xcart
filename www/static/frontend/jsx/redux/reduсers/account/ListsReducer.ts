import { AnyAction } from "redux";
import { AccountListsStore } from "../../../modules/account/ts/types/account-store.type";
import { accountListsInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";
import { reorderList } from "../../actions/account-actions/ListsActions";
import { reorderMass } from "../../../modules/account/utils/reorder-mass";

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
              products: reorderMass(
                e.products,
                action.startIndex,
                action.endIndex
              ),
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
