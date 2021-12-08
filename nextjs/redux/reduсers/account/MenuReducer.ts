import { AnyAction } from "redux";

const initialValue = {
  isMobileMenuVisible: false,
  isTabletMenuVisible: false,
};

const MenuReducer = (
  store: Record<any, any> = initialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "SET_MOBILE_MENU_VISIBLE":
      store.isMobileMenuVisible = action.isMobileMenuVisible;
      return store;

    case "SET_TABLET_MENU_VISIBLE":
      store.isTabletMenuVisible = action.isTabletMenuVisible;
      return store;

    case "HIDE_ALL_MENU":
      store.isMobileMenuVisible = false;
      store.isTabletMenuVisible = false;
      return store;

    default:
      return store;
  }
};

export default MenuReducer;
