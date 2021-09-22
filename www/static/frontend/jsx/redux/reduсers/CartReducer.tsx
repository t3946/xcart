import { AnyAction } from "redux";
import { cartInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

const CartReducer = (
  store: Record<any, any> = cartInitialValue,
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
