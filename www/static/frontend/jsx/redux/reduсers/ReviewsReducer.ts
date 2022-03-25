import { AnyAction } from "redux";
import { productsRatingsInitialValue } from "@client/modules/account/ts/consts/store-initial-value";
import _unset from "lodash/unset";

const ReviewsReducer = (
  store: Record<number, any> = productsRatingsInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "ADD_REVIEWS":
      const { productId, reviews, country } = action.payload;

      if (!store[productId]) {
        store[productId] = {
          country: null,
          reviews: [],
        };
      }

      const oldReviews = store[productId].reviews;

      store[productId].country = country;
      store[productId].reviews = [...oldReviews, ...reviews];
      return { ...store };

    case "CLEAR_REVIEWS":
      _unset(store, action.payload.productId);
      return { ...store };

    default:
      return store;
  }
};

export default ReviewsReducer;
