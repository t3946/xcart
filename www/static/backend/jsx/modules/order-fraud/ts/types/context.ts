import { SettingsFraudOrder } from "@admin/modules/order-fraud/ts/types/settings";
import { AnswerFraudOrder } from "@admin/modules/order-fraud/ts/types/answer";

export interface FraudOrderContext {
  orderId: number | string;
  settings: SettingsFraudOrder;
  setSettings: any;
  fraudManual: {};
  setFraudManual: (event) => void;
  answer?: AnswerFraudOrder;
  dialog: { get: boolean; set: () => void };
  template: { get: string; set: (template) => void };
}
