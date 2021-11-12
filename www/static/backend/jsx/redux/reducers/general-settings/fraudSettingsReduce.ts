import { ResponseFraudAllSettings } from "@admin/modules/general-settings/ts/types/fraud-check/response";
import { AnyAction } from "redux";
import { initStateFraudSettings } from "@admin/modules/general-settings/ts/consts/fraud-check/default-state";

const fraudSettingsReducer = (
  state: ResponseFraudAllSettings = initStateFraudSettings,
  action: AnyAction
) => {
  switch (action.type) {
    case "SET_FRAUD_SETTINGS":
      return { ...state, ...action.data };
    case "SET_FA_QUESTION_DATA":
      const question = state.faQuestions[action.question.type].data.find(
        (question) => question.questionId === action.question.questionId
      );
      if (question) {
        question.value = action.question.weight;
        question.template = action.question.template;
      }
      return { ...state };
    case "SET_BASE_QUESTION_DATA":
      const baseQuestion = state.baseQuestions.find(
        (question) => question.questionId === action.question.questionId
      );
      if (baseQuestion) {
        baseQuestion.weight = action.question.weight;
        baseQuestion.template = action.question.template;
      }
      return { ...state };
    default:
      return state;
  }
};
export default fraudSettingsReducer;
