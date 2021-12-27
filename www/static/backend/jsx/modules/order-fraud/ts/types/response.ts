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

export interface ResponseAnswerResult {
  bareResult: number;
  overallResult: number;
  answerList: {
    fraud_result: string;
    fraud_score: number;
    question_code: string;
    outcome: number;
  }[];
}
