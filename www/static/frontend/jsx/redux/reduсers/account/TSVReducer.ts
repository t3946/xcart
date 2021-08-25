import { AnyAction } from "redux";
import { TSV } from "@client/jsx/modules/account/ts/consts/account-store-initial-value";

const TSVReducer = (
  state: Record<any, any> = TSV,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "ACCOUNT_TSV_SET":
      return action.payload;
    default:
      return state;
  }
};
export default TSVReducer;
