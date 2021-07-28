import { AnyAction } from "redux";
import { accountMenuInitialValue } from "../../../modules/account/ts/consts/account-store-initial-value";

const MenuReducer = (
  state = accountMenuInitialValue,
  action: AnyAction
) => {
  console.log(action);
  switch (action.type) {
    case "SET_MOBILE_MENU_VISIBLE":
      state.isMobileMenuVisible = action.isMobileMenuVisible;
      return state;

    case "SET_TABLET_MENU_VISIBLE":
      state.isTabletMenuVisible = action.isTabletMenuVisible;
      return state;

    case "HIDE_ALL_MENU":
      state.isMobileMenuVisible = false;
      state.isTabletMenuVisible = false;
      return state;

    default:
      return state;
  }
};

export default MenuReducer;
