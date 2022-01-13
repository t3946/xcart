import { OrdersStore } from "@modules/account/ts/types/order/orders-store.types";
import { AnyAction } from "redux";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
const initialValue: OrderView = {
  orderData: null,
};
const OrderViewReducer = (
  state: OrderView = initialValue,
  action: AnyAction
): OrderView => {
  switch (action.type) {
    case "SET_ORDER_VIEW":
      return { ...state, orderData: action.order };
    default:
      return state;
  }
};
export default OrderViewReducer;
