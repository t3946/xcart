import { AnyAction } from "redux";
import { OrdersStore } from "@client/modules/account/ts/types/account-store.type";
import { accountOrdersInitialValue } from "@client/modules/account/ts/consts/account-store-initial-value";

const OrdersReducer = (
  state: OrdersStore = accountOrdersInitialValue,
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
