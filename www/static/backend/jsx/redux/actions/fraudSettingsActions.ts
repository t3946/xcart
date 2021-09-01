import { ResponseFraudAllSettings } from "@admin/modules/general-settings/ts/types/fraud-check/response";
import { FormDataFraud } from "@admin/modules/general-settings/ts/types/fraud-check/data";

export const setFraudSettings = (): any => ({
  type: "SET_SETTINGS",
});

export const changeFraudSettingsForm = (form: FormDataFraud) => ({
  type: "CHANGE_SETTINGS_FORM",
  form,
});
