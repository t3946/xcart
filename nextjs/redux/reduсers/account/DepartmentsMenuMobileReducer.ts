import { AnyAction } from "redux";
import { departmentsMenuMobile } from "@modules/account/ts/consts/store-initial-value";

const DepartmentsMobileMenuReducer = (
  store: { isVisible: boolean } = departmentsMenuMobile,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "DEPARTMENTS_MENU_MOBILE_SET_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};
export default DepartmentsMobileMenuReducer;
