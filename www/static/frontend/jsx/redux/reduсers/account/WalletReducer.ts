import { AnyAction } from "redux";
import { AccountWalletStoreDto } from "../../../modules/account/ts/types/account-store.type";
import { accountWalletStoreInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const accountWalletReducer = (
  state: AccountWalletStoreDto = accountWalletStoreInitialValue,
  action: AnyAction
): AccountWalletStoreDto => {
  switch (action.type) {
    case "GET_CARDS":
      return { ...state, cardsLoading: true };
    case "SET_CARDS":
      return {
        ...state,
        cards: action.cards,
        cardsLoading: false,
        submitCardFormLoading: false,
        submitFormData: {},
      };
    case "ADD_SUBMIT_DATA":
      return { ...state, submitFormData: action.data };
    case "ADD_CARD":
      return {
        ...state,
        submitCardFormLoading: true,
      };
    case "CHANGE_DEFAULT_CARD":
      return {
        ...state,
        submitCardFormLoading: true,
      };
    case "EDIT_CARD":
      return {
        ...state,
        submitCardFormLoading: true,
      };
    case "REMOVE_CARD":
      return {
        ...state,
        submitCardFormLoading: true,
      };
    default:
      return state;
  }
};
export default accountWalletReducer;
