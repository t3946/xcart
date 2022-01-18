import { SelectDate } from "@modules/account/ts/types/order/orders-store.types";

export const ordersHeaderSelectValues: SelectDate[] = [
  {
    value: new Date().setDate(new Date().getDate() - 7),
    viewValue: "Last 7 days",
  },
  {
    value: new Date().setDate(new Date().getDate() - 30),
    viewValue: "Last 30 days",
  },
  {
    value: new Date().setDate(new Date().getDate() - 90),
    viewValue: "Last 90 days",
  },
  {
    value: null,
    viewValue: "All time",
  },
];
