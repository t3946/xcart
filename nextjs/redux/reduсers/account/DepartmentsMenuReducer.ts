import { AnyAction } from "redux";

const DepartmentsMenuReducer = (
  store: Record<any, any>[] | null = null,
  action: AnyAction
): Record<any, any>[] | null => {
  switch (action.type) {
    default:
      return store;
  }
};
export default DepartmentsMenuReducer;
