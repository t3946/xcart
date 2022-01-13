import { AnyAction } from "redux";
import { AccountAddressesStore } from "@modules/account/ts/types/store.type";

const initialState = {
  loading: false,
  addressesList: undefined,
};

const accountAddressesReducer = (
  state: AccountAddressesStore = {
    loading: false,
    addressesList: undefined,
  },
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
