import { AnyAction } from "redux";
import { AccountStoreDto } from "../../../modules/account/ts/types/account-store.type";
import { accountStoreInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const accountAddressesReducer = (
  state: AccountStoreDto = accountStoreInitialValue,
  action: AnyAction
): AccountStoreDto => {
  switch (action.type) {
    case "GET_ADDRESSES":
      return { ...state, loading: true };
    case "CHANGE_DEFAULT_ADDRESS":
      return { ...state, loading: true };
    case "REMOVE_ADDRESS":
      return { ...state, loading: true };
    case "SET_ADDRESSES":
      return { ...state, loading: false, addresses: action.addresses };
    default:
      return state;
  }
};
export default accountAddressesReducer;
