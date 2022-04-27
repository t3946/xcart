import { AnyAction } from "redux";

const initialValue = null;

const ProductReducer = (
  store: Record<any, any> = initialValue,
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "SET_PRODUCT":
      return { ...action.product };

    default:
      return store;
  }
};

export default ProductReducer;
