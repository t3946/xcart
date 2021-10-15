import { SelectValue } from "@client/modules/account/ts/types/select-value.type";

export const getOrders = (ordersType: string): any => ({
  type: "GET_ORDERS",
  ordersType,
});

export const changeTimeGap = (
  ordersType: string,
  newValue: SelectValue<number, string>
): any => ({
  type: "CHANGE_TIME_GAP",
  ordersType,
  newValue,
});
