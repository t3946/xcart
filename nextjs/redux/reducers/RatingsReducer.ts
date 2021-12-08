import { AnyAction } from "redux";
import { productsRatingsInitialValue } from "@modules/account/ts/consts/store-initial-value";

const RatingsReducer = (
  store: Record<any, any> = productsRatingsInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "SAVE_PRODUCT_RATINGS":
      store.quantity = action.quantity;
      store[action.productId] = action.ratings;

      return { ...store };
    default:
      return store;
  }
};

export default RatingsReducer;
