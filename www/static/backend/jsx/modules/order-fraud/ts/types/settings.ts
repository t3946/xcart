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

export interface FraudResultCheck {
  bare_result: number | string;
  risk_score: number | string;
}
export interface ColumnLegendData {
  description: string;
  columnName: string;
  value:
    | string
    | {
        street1?: string;
        street2?: string;
        state: string;
        city: string;
        zipcode: string;
        country: ?string;
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
