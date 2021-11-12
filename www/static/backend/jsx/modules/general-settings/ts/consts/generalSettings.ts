import { initStateFraudSettings } from "@admin/modules/general-settings/ts/consts/fraud-check/default-state";
import {
  GeneralSettingsAlert,
  StoreGeneralSettings,
} from "@admin/modules/general-settings/ts/types/general-settings/generalSettings.type";

export const initialSettingsAlert: GeneralSettingsAlert = {
  state: false,
  status: "success",
  message: "",
};

export const initStateGeneralSettings: StoreGeneralSettings = {
  fraudSettings: initStateFraudSettings,
  alert: initialSettingsAlert,
};
