import { AnyAction } from "redux";
import { productsRatingsInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

const ReviewsReducer = (
  store: Record<any, any> = productsRatingsInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "ADD_PRODUCT_REVIEWS":
      if (!store[action.productId]) {
        store[action.productId] = [];
      }

      store[action.productId] = store[action.productId].concat(action.reviews);

      return { ...store };
    default:
      return store;
  }
};

export default ReviewsReducer;
