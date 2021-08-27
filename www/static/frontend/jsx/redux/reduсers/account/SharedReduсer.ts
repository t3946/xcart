import { AnyAction } from "redux";
import { AccountMainStore } from "../../../modules/account/ts/types/account-store.type";
import { accountMainStoreInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const accountSharedReducer = (
  state: AccountMainStore = accountMainStoreInitialValue,
  action: AnyAction
): AccountMainStore => {
  switch (action.type) {
    case "SET_TERRITORY":
      return { ...state, states: action.states, countries: action.countries };
    case "SET_BREAKPOINT":
      return {
        ...state,
        breakpoint: action.breakpoint,
      };
    default:
      return state;
  }
};
export default accountSharedReducer;
