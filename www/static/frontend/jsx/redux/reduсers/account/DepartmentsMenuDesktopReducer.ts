import { AnyAction } from "redux";
import { departmentsMenuDesktop } from "@client/modules/account/ts/consts/store-initial-value";

const DepartmentsDesktopMenuReducer = (
  store: Record<any, any> = departmentsMenuDesktop,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "DEPARTMENTS_MENU_DESKTOP_SET_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};
export default DepartmentsDesktopMenuReducer;
