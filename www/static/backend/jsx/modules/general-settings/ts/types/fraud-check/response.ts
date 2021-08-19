import {
  FormData,
  SettingsList,
} from "@admin/modules/general-settings/ts/types/fraud-check/data";

export interface ResponseFraudSave {
  status: boolean;
  error?: string;
}
export interface ResponseFraudGet {
  status: boolean;
  data: FormData;
  settings: SettingsList;
}
export interface ResponseFraudTableGet {}
