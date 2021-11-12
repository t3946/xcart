export interface SettingsFraudOrder {
  lock?: {
    status: boolean;
    timeUnlocked: string;
  };
  locked_orders: boolean;
  lang?: {
    basement: string;
  };
  status: { code: string; name: string }[];
  column_fn: any;
  column_address: any;
  manual_action: any;
  bare_result: number;
  overall_result: number;
  risk_score: number;
  fraud_status: {
    code: string;
    name: string;
  };
  legend: {
    full_name: ColumnLegendData[];
    address: ColumnLegendData[];
  };
  order_prefix: string;
}

export interface FraudFAQuestion {
  question_id: string | number;
  f_fraud_id: string | number;
  t_fraud_id: string | number;
  weight: string | number;
  template: string;
}

export interface FraudResultCheck {
  bare_result: number | string;
  overall_result: number | string;
  risk_score: number | string;
}
export interface ColumnLegendData {
  description: string;
  columnName: string;
  value:
    | string
    | {
        state: string;
        city: string;
        zipcode: string;
      };
  link: boolean;
  linkUrl: string;
  type: string;
  frontendType: string;
  provider: string;
  sourceType: string;
  isMelissa: boolean;
  inferredFrom: string;
}
