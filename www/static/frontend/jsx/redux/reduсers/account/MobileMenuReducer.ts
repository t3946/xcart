import { AnyAction } from "redux";
import { accountMobileMenuInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const accountAddressesReducer = (
  state = accountMobileMenuInitialValue,
  action: AnyAction
) => {
  switch (action.type) {
    case "SET_MOBILE_MENU_VISIBLE":
      state.isVisible = action.isVisible;
      return state;

    default:
      return state;
  }
};

export default accountAddressesReducer;
