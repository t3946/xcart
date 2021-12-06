import { AnyAction } from "redux";

const MiniCartReducer = (
  store: Record<any, any>,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "SET_IS_VISIBLE":
      store.isVisible = action.isVisible;
      return { ...store };
    default:
      return store;
  }
};

export default MiniCartReducer;
