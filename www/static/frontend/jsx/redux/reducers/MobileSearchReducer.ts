import { AnyAction } from "redux";

const initialState = { isVisible: false };

const MobileSearchReducer = (
  store: { isVisible: boolean } = initialState,
  action: AnyAction
): Record<any, any> => {
  if (store === null) return store;

  switch (action.type) {
    case "MOBILE_SEARCH_TOGGLE_VISIBLE":
      store.isVisible = !store.isVisible;
      return { ...store };
    case "MOBILE_SEARCH_SET_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};
export default MobileSearchReducer;
