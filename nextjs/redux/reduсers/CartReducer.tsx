import { AnyAction } from "redux";

const CartReducer = (
  store: { quantity: number; checkoutUrl: string },
  action: AnyAction
): Record<any, any> => {
  switch (action.type) {
    case "CART_SET_QUANTITY":
      store.quantity = action.quantity;
      return { ...store };
    default:
      return store;
  }
};

export default CartReducer;
