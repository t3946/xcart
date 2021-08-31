import {
  FormDataFraud,
  SettingsList,
} from "@admin/modules/general-settings/ts/types/fraud-check/data";
import { TableDataResponse } from "@admin/modules/general-settings/ts/types/fraud-check/data-table";

export interface ResponseFraudSave {
  status: boolean;
  error?: string;
}
export interface ResponseQuestionUpdate {
  update: boolean;
}
export interface ResponseFraudAllSettings {
  faQuestions: {
    address?: { data: TableDataResponse[]; columns: string[] };
    full_name?: { data: TableDataResponse[]; columns: string[] };
  };
  baseQuestions: [];
  settings: {
    data: FormDataFraud;
    settings: SettingsList;
  };
}
