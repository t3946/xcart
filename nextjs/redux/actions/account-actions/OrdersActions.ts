import { SelectValue } from "@modules/account/ts/types/select-value.type";

export const getOrders = (ordersType: string): any => ({
  type: "GET_ORDERS",
  ordersType,
});

export const setOrders = (orders: any[], orderType: string): any => ({
  type: "SET_ORDERS",
  orders,
  orderType,
});

export const changeTimeGap = (
  ordersType: string,
  newValue: SelectValue<number, string>
): any => ({
  type: "CHANGE_TIME_GAP",
  ordersType,
  newValue,
});

export const sendEmail = (email: any, onSend: () => void): any => ({
  type: "SEND_EMAIL",
  email,
  onSend,
});
