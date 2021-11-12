import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";

export const initialFraudCheckStore: FraudCheckStore = {
  loading: true,
  noCheck: false,
  orderId: null,
  templateView: null,
  data: null,
  alert: {
    state: false,
    message: null,
    status: "success",
  },
};
export const initialMatchingAddress = [
  { value: "0", description: "different Countries" },
  { value: "1/6", description: "the same Country (USA)" },
  { value: "2/6", description: "the same State" },
  { value: "3/6", description: "the same City" },
  { value: "4/6", description: "the same Zip code" },
  { value: "5/6", description: "the same Street (Line 1)" },
  { value: "6/6", description: "the same Street (Line 2)" },
];
