export interface FormDataFraud {
  Under_review_users: string[];
  fraud_domains_free_email_provider: string;
  Overall_RS_threshold_for_Clear_status: number;
  Risk_Score_Threshold_status: string;
  Overall_FC_threshold_for_Clear_status: number;
  Threshold_status: string;
  below_threshold_status: string;
  fraud_Google_address_search_exclusions: string;
  fraud_Google_phone_search_exclusions: string;
  fraud_Google_email_search_exclusions: string;
  fraudulent_domains: string;
}
export interface FraudUsers {
  id: number | string;
  firstname: string;
}
export interface FraudStatus {
  code: string;
  name: string;
}
export interface SettingsList {
  users: FraudUsers[];
  status: FraudStatus[];
}
export interface ChangeQuestionDataForm {
  weight: number;
  questionCode: string;
  template: string;
  orderBy?: number;
  questionId: number;
  type: string;
}
