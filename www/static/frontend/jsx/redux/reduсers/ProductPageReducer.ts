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

    default:
      return store;
  }
};

export default ProductPageReducer;
