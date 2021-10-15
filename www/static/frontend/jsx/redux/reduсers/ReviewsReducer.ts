import { AnyAction } from "redux";
import { productsRatingsInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";
import { unset } from "lodash";

const ReviewsReducer = (
  store: Record<number, any> = productsRatingsInitialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "ADD_PRODUCT_REVIEWS":
      if (!store[action.productId]) {
        store[action.productId] = [];
      }

      store[action.productId] = store[action.productId].concat(action.reviews);

      return { ...store };

    case "SET_HELPFUL":
      for (const product_id in store) {
        const reviews = store[product_id];

        for (let i = 0; i < reviews.length; i++) {
          const reviewId = parseInt(reviews[i].product_review_id);

          if (reviewId === action.reviewId) {
            reviews[i].markedHelpful = action.helpful;
            return { ...store };
          }
        }
      }

      return { ...store };

    case "ADD_REVIEWS":
      const { productId } = action.payload;

      if (!store[productId]) {
        store[productId] = [];
      }

      store[productId] = [...store[productId], ...action.payload.reviews];
      return { ...store };

    case "CLEAR_REVIEWS":
      unset(store, action.payload.productId);
      return { ...store };

    default:
      return store;
  }
};

export default ReviewsReducer;
