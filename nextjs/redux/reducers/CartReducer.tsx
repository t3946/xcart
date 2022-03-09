import { AnyAction } from "redux";

interface IStore {
  checkoutUrl: string;
  items: any;
  groups: any;
  total: number;
  discount: number;
  quantity: number;
  currency: string;
}

const CartReducer = (
  store: IStore | null = null,
  action: AnyAction
): Record<any, any> | null => {
  if (store === null) return store;

  switch (action.type) {
    case "CART_SET":
      return { ...action.payload.cart };

    case "CART_EMPTY":
      store.items = [];
      store.total = 0;
      store.quantity = 0;
      return { ...store };

    default:
      return store;
  }
};

export default CartReducer;
