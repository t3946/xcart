import {AnyAction} from "redux";
import {siteInitialValue} from "@client/modules/account/ts/consts/store-initial-value";

const SiteReducer = (
  store: any = siteInitialValue,
  action: AnyAction
): Record<any, any>[] => {
  switch (action.type) {
    case "PRODUCT_INFO_SET":
      store.product_info = action.productInfo;
      return {...store};
    case "REVIEWS_SETTINGS_SET":
      store.reviews = action.reviews;
      return {...store};

    case "SITE_SET":
      return {...action.site};

    default:
      return store;
  }
};
export default SiteReducer;
