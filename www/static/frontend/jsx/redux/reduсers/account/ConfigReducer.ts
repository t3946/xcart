import { AnyAction } from "redux";
import { configInitialValue } from "@client/modules/account/ts/consts/store-initial-value";

const ConfigReducer = (
  store: Record<any, any>[] = configInitialValue,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    default:
      return store;
  }
};
export default ConfigReducer;
