export interface SettingsFraudOrder {
  lock?: {
    status: boolean;
    timeUnlocked: string;
  };
  lang?: {
    basement: string;
  };
  status: { code: string; name: string }[];
  column_fn: any;
  column_address: any;
}
export interface FraudFAQuestion {
  question_id: string | number;
  f_fraud_id: string | number;
  t_fraud_id: string | number;
  weight: string | number;
  template: string;
}
