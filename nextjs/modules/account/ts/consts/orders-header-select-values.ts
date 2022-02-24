import { SelectDate } from "@modules/account/ts/types/order/orders-store.types";

const nowTimeS = Math.floor(new Date().getTime() / 1000);
const dayTimeS = 60 * 60 * 24;

export const ordersHeaderSelectValues: SelectDate[] = [
  {
    value: nowTimeS - dayTimeS * 7,
    label: "Last 7 days",
  },
  {
    value: nowTimeS - dayTimeS * 30,
    label: "Last 30 days",
  },
  {
    value: nowTimeS - dayTimeS * 90,
    label: "Last 90 days",
  },
  {
    value: 0,
    label: "All time",
  },
];
