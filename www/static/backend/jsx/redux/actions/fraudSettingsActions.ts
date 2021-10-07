import { ResponseFraudAllSettings } from "@admin/modules/general-settings/ts/types/fraud-check/response";
import {
  ChangeQuestionDataForm,
  FormDataFraud,
} from "@admin/modules/general-settings/ts/types/fraud-check/data";

export const setFraudSettings = (): any => ({
  type: "SET_SETTINGS",
});

export const changeFraudSettingsForm = (form: FormDataFraud) => ({
  type: "CHANGE_SETTINGS_FORM",
  form,
});

export const changeFraudFAQuestionData = (
  question: ChangeQuestionDataForm
) => ({
  type: "CHANGE_FA_QUESTION_DATA",
  question,
});
export const changeFraudBaseQuestionData = (
  question: ChangeQuestionDataForm
) => ({
  type: "CHANGE_BASE_QUESTION_DATA",
  question,
});
