import { AnyAction } from "redux";
import { departmentsMenuMobile } from "@client/modules/account/ts/consts/account-store-initial-value";

const DepartmentsMenuReducer = (
  store: Record<any, any> = departmentsMenuMobile,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "DEPARTMENTS_MENU_MOBILE_SHOW":
      return { isVisible: true };
    case "DEPARTMENTS_MENU_MOBILE_HIDE":
      return { isVisible: false };
    case "DEPARTMENTS_MENU_MOBILE_TOGGLE":
      return { isVisible: !store.isVisible };
    default:
      return store;
  }
};
export default DepartmentsMenuReducer;
