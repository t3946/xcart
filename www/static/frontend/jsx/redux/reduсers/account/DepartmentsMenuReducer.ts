import { AnyAction } from "redux";
import { departmentsMenu } from "@client/modules/account/ts/consts/account-store-initial-value";

const DepartmentsMenuReducer = (
  store: Record<any, any>[] = departmentsMenu,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    default:
      return store;
  }
};
export default DepartmentsMenuReducer;
