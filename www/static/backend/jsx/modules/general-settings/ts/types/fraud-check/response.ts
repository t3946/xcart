import {
  FormDataFraud,
  SettingsList,
} from "@admin/modules/general-settings/ts/types/fraud-check/data";
import {
  TableBaseQuestion,
  TableDataResponse,
} from "@admin/modules/general-settings/ts/types/fraud-check/question-data.type";

export interface ResponseFraudSave {
  status: boolean;
  error?: string;
}
export interface ResponseFraudDataUpdate {
  update: boolean;
}
export interface ResponseFraudAllSettings {
  faQuestions: {
    address?: { data: TableDataResponse[]; columns: string[] };
    full_name?: { data: TableDataResponse[]; columns: string[] };
  };
  baseQuestions: TableBaseQuestion[];
  settings: {
    data: FormDataFraud;
    settings: SettingsList;
  };
}
