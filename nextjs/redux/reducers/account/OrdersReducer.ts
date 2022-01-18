import { AnyAction } from "redux";
import { ordersHeaderSelectValues } from "@modules/account/ts/consts/orders-header-select-values";
import { OrdersStore } from "@modules/account/ts/types/order/orders-store.types";

const initialValue: OrdersStore = {
  loading: false,
  selectDate: ordersHeaderSelectValues[0],
  orders: [],
};

const OrdersReducer = (
  state: OrdersStore = initialValue,
  action: AnyAction
): OrdersStore => {
  switch (action.type) {
    case "GET_ORDERS":
      return { ...state, loading: true };
    case "SET_ORDERS":
      return {
        ...state,
        loading: false,
        orders: action.orders,
      };
    case "CHANGE_TIME_GAP":
      return {
        ...state,
        selectDate: action.newValue,
      };
    case "SEND_EMAIL":
      return {
        ...state,
        loading: true,
      };
    case "STOP_LOADING":
      return {
        ...state,
        loading: false,
      };
    default:
      return state;
  }
};
export default OrdersReducer;
