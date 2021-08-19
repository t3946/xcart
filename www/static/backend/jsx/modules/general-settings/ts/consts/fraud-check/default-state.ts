import { FormDataFraud } from "@admin/modules/general-settings/ts/types/fraud-check/data";

export const defaultStateForm: FormDataFraud = {
  Under_review_users: [],
  fraud_domains_free_email_provider: "",
  Overall_RS_threshold_for_Clear_status: "",
  Risk_Score_Threshold_status: "",
  Overall_FC_threshold_for_Clear_status: "",
  Threshold_status: "",
  below_threshold_status: "",
  fraud_Google_address_search_exclusions: "",
  fraud_Google_phone_search_exclusions: "",
  fraud_Google_email_search_exclusions: "",
};
