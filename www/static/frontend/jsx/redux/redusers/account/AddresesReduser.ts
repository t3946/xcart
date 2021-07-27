import { AnyAction } from "redux";
import { AccountAddressesStoreDto } from "../../../modules/account/ts/types/account-store.type";
import { accountAddressesInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const accountAddressesReducer = (
  state: AccountAddressesStoreDto = accountAddressesInitialValue,
  action: AnyAction
): AccountAddressesStoreDto => {
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
