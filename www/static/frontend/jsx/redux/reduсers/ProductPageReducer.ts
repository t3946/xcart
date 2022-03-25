import { AnyAction } from "redux";

const initialValue = {
  isUserCanWriteReview: false,
};

const ProductPageReducer = (
  store: Record<any, any> = initialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "SET_IS_USER_CAN_WRITE_REVIEW":
      store.isUserCanWriteReview = action.isUserCanWriteReview;

      return { ...store };
    case "PRODUCT_PAGE_SET_REVIEWS":
      return {...store, reviews: action.reviews};

    case "SET_HELPFUL":
      for (const review of store.reviews) {
        if (review.product_review_id === action.reviewId) {
          review.marked_helpful = action.helpful;
          review.helpful_total += review.marked_helpful ? 1 : -1;
          break;
        }
      }

      return {...store};
    case "PRODUCT_PAGE_SET_COUNTRY":
      store.country = action.country;

      return { ...store };
    default:
      return store;
  }
};

export default ProductPageReducer;
