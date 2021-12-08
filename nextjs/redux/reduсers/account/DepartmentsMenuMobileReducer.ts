import { AnyAction } from "redux";

const initialState = {
  isVisible: false,
};

const DepartmentsMobileMenuReducer = (
  store: { isVisible: boolean } | null = initialState,
  action: AnyAction
): Record<any, any> | null => {
  if (store === null) return store;

  switch (action.type) {
    case "DEPARTMENTS_MENU_MOBILE_SET_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};
export default DepartmentsMobileMenuReducer;
