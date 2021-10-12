import { AnyAction } from "redux";
import { productsRatingsInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

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
    case "SET_HELPFUL": {
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
    }
    default:
      return store;
  }
};

export default ReviewsReducer;
