import { AnyAction } from "redux";
import { cartInitialValue } from "@client/modules/account/ts/consts/store-initial-value";

const MiniCartReducer = (
  store: Record<any, any> = cartInitialValue,
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
