import { OrdersStore } from "@modules/account/ts/types/order/orders-store.types";
import { AnyAction } from "redux";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";

const OrderViewReducer = (
  state: OrderView = null,
  action: AnyAction
): OrderView => {
  switch (action.type) {
    case "SET_ORDER_VIEW":
      return { ...state, ...action.order };
    default:
      return state;
  }
};
export default OrderViewReducer;
