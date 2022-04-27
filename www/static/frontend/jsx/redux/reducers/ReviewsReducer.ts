import { AnyAction } from "redux";
import {
  productsReviewsInitialValue
} from "@client/modules/account/ts/consts/store-initial-value";
import _unset from "lodash/unset";

const ReviewsReducer = (
  store: Record<number, any> = productsReviewsInitialValue,
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

    case "REVIEWS_SET_TOTAL":
      store[action.productId].total = action.total;
      return { ...store };

    case "CLEAR_REVIEWS":
      store[action.payload.productId].reviews = [];
      return { ...store };

    default:
      return store;
  }
};

export default ReviewsReducer;
