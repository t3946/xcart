import { AnyAction } from "redux";

const initialState = { isVisible: false };

const DepartmentsDesktopMenuReducer = (
  store: { isVisible: boolean } = initialState,
  action: AnyAction
): Record<any, any> => {
  if (store === null) return store;

  switch (action.type) {
    case "DEPARTMENTS_MENU_DESKTOP_SET_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};
export default DepartmentsDesktopMenuReducer;
