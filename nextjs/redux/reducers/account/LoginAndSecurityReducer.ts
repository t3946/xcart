import { AnyAction } from "redux";
import { AccountLoginAndSecurityStore } from "@modules/account/ts/types/store.type";

const initialValue = {
  alert: null,
};

const LoginAndSecurityReducer = (
  state: AccountLoginAndSecurityStore = initialValue,
  action: AnyAction
): AccountLoginAndSecurityStore => {
  switch (action.type) {
    case "SET_ALERT":
      state.alert = action.alert;
      return { ...state };

    default:
      return state;
  }
};
export default LoginAndSecurityReducer;
