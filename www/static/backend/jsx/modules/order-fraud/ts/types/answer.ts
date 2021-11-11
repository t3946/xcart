export interface AnswerFraudOrder {
  diagonal?: MainAnswer[];
  red_flags: MainAnswer[];
  payment?: PaymentAnswer;
  full_name: FAAnswer[];
  address: FAAnswer[];
}
export interface PaymentAnswer {
  general_payment: MainAnswer[] | [];
  pay_pal?: MainAnswer[] | [];
  stripe?: MainAnswer[] | [];
}
export interface MainAnswer {
  fraud_result: string;
  fraud_score: string | number;
  question_id: string | number;
  template: string;
  question_code: string;
  question_weight: number | string;
  question_auto: string;
  manual_action: string;
}
export interface FAAnswer {
  fraud_result: string;
  fraud_score: number;
  template: string;
  question_weight: number | string;
  f_fraud_name: number | string;
  t_fraud_name: number | string;
  manual_action: string;
  outcome: number | string;
}
