import { AnyAction } from "redux";
import { AccountPaymentsStore } from "@modules/account/ts/types/store.type";
import { accountPaymentsStoreInitialValue } from "@modules/account/ts/consts/store-initial-value";

const accountWalletReducer = (
  state: AccountPaymentsStore = accountPaymentsStoreInitialValue,
  action: AnyAction
): AccountPaymentsStore => {
  switch (action.type) {
    case "GET_CARDS":
      return { ...state, cardsLoading: true };
    case "SET_CARDS":
      return {
        ...state,
        cards: action.cards,
        cardsLoading: false,
        submitCardFormLoading: false,
        submitFormData: null,
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
    case "SET_TRANSACTIONS":
      return {
        ...state,
        transactions: action.transactions,
      };
    default:
      return state;
  }
};
export default accountWalletReducer;
