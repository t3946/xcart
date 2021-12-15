import { AnyAction } from "redux";
import { AccountMobileAlertStore } from "@modules/account/ts/types/store.type";
// import { accountMobileAlert } from "@modules/account/ts/consts/store-initial-value";

const initialState = {
  alert: null,
};

const MobileAlertReducer = (
  state: AccountMobileAlertStore = initialState,
  action: AnyAction
): AccountMobileAlertStore => {
  switch (action.type) {
    case "SET_MOBILE_ALERT":
      state.alert = action.alert;
      return { ...state };

    case "SET_IS_VISIBLE_ALERT":
      state.isVisible = action.isVisible;
      return { ...state };

    default:
      return state;
  }
};
export default MobileAlertReducer;
