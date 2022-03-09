import { AnyAction } from "redux";
import { AccountLoginAndSecurityStore } from "@client/modules/account/ts/types/store.type";
import { accountLoginAndSecurityValue } from "@client/modules/account/ts/consts/store-initial-value";

const LoginAndSecurityReducer = (
  state: AccountLoginAndSecurityStore = accountLoginAndSecurityValue,
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
