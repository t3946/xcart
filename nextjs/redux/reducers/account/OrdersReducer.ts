import { AnyAction } from "redux";
import { OrdersStore } from "@modules/account/ts/types/store.type";
import { ordersHeaderSelectValues } from "@modules/account/ts/consts/orders-header-select-values";

const initialValue = {
  ordersLoading: false,
  orders: {
    open: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
    cancelled: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
    completed: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
  },
};

const OrdersReducer = (
  state: OrdersStore = initialValue,
  action: AnyAction
): OrdersStore => {
  switch (action.type) {
    case "GET_ORDERS":
      return { ...state, ordersLoading: true };
    case "SET_ORDERS":
      state.orders[action.orderType].items = action.orders;
      return {
        ...state,
        ordersLoading: false,
        orders: {
          ...state.orders,
        },
      };
    case "CHANGE_TIME_GAP":
      state.orders[action.ordersType].selectValue = action.newValue;
      return {
        ...state,
        orders: {
          ...state.orders,
        },
      };
    case "SEND_EMAIL":
      return {
        ...state,
        ordersLoading: true,
      };
    case "STOP_LOADING":
      return {
        ...state,
        ordersLoading: false,
      };
    default:
      return state;
  }
};
export default OrdersReducer;
