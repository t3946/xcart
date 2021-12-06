import { AnyAction } from "redux";
import { departmentsMenuDesktop } from "@modules/account/ts/consts/store-initial-value";

const DepartmentsDesktopMenuReducer = (
  store: { isVisible: boolean } = departmentsMenuDesktop,
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
