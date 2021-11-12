import { ResponseFraudAllSettings } from "@admin/modules/general-settings/ts/types/fraud-check/response";

export interface StoreGeneralSettings {
  fraudSettings: ResponseFraudAllSettings;
  alert: GeneralSettingsAlert;
}
export interface GeneralSettingsAlert {
  state: boolean;
  message: string;
  status: string;
}
