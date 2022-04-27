import { AnyAction } from "redux";
import { AccountAddressesStore } from "@client/jsx/modules/account/ts/types/store.type";
import { accountAddressesInitialValue } from "@client/modules/account/ts/consts/store-initial-value";

const accountAddressesReducer = (
  state: AccountAddressesStore = accountAddressesInitialValue,
  action: AnyAction
): AccountAddressesStore => {
  switch (action.type) {
    case "GET_ADDRESSES":
      return { ...state, loading: true };
    case "CHANGE_DEFAULT_ADDRESS":
      return { ...state, loading: true };
    case "REMOVE_ADDRESS":
      return { ...state, loading: true };
    case "EDIT_ADDRESS":
      return { ...state, addressFormLoading: true };
    case "ADD_ADDRESS":
      return { ...state, addressFormLoading: true };
    case "SET_ADDRESSES":
      return {
        ...state,
        loading: false,
        addressFormLoading: false,
        addressesList: action.addresses,
      };
    default:
      return state;
  }
};
export default accountAddressesReducer;
