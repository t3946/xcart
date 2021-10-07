import {
  FraudResultCheck,
  SettingsFraudOrder,
} from "@admin/modules/order-fraud/ts/types/settings";
import { AnswerFraudOrder } from "@admin/modules/order-fraud/ts/types/answer";

export interface ResponseFraudCheckOrder {
  status: boolean;
  settings: SettingsFraudOrder;
  answer: AnswerFraudOrder;
}

export interface ResponseFraudChangeStatus {
  status: boolean;
  error?: string;
}

export interface ResponseForceFraudCheck {
  status: boolean;
  error?: string;
}

export interface ResponseChangeResultFraudCheck {
  status: boolean;
  error?: string;
  fraud_result?: FraudResultCheck;
}
