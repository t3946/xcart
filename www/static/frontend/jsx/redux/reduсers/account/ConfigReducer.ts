import { AnyAction } from "redux";
import { configInitialValue } from "@client/modules/account/ts/consts/store-initial-value";

const ConfigReducer = (
  store: Record<any, any>[] = configInitialValue,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    case "CONFIG_SET":
      return {...action.config};
    default:
      return store;
  }
};
export default ConfigReducer;
