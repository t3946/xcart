import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";

export const initialFraudCheckStore: FraudCheckStore = {
  loading: true,
  noCheck: null,
  orderId: null,
  templateView: null,
  data: null,
  alert: {
    state: false,
    message: null,
    status: "success",
  },
};
