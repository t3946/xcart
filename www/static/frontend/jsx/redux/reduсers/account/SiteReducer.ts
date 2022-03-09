import { AnyAction } from "redux";
import { siteInitialValue } from "@client/modules/account/ts/consts/store-initial-value";

const SiteReducer = (
  store: Record<any, any>[] = siteInitialValue,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    default:
      return store;
  }
};
export default SiteReducer;
