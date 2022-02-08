import { SelectDate } from "@modules/account/ts/types/order/orders-store.types";

export const ordersHeaderSelectValues: SelectDate[] = [
  {
    value: new Date().setDate(new Date().getDate() - 7),
    label: "Last 7 days",
  },
  {
    value: new Date().setDate(new Date().getDate() - 30),
    label: "Last 30 days",
  },
  {
    value: new Date().setDate(new Date().getDate() - 90),
    label: "Last 90 days",
  },
  {
    value: null,
    label: "All time",
  },
];
