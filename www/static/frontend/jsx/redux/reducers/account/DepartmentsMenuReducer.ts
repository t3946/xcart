import { AnyAction } from "redux";
import { departmentsMenu } from "@client/modules/account/ts/consts/store-initial-value";

const DepartmentsMenuReducer = (
  store: Record<any, any>[] = departmentsMenu,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    case "DEPARTMENTS_MENU_SET":
      return {...action.departmentsMenu};
    default:
      return store;
  }
};
export default DepartmentsMenuReducer;
