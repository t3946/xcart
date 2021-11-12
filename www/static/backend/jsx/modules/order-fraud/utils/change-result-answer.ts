import { FraudCheckData } from "@admin/modules/order-fraud/ts/types/redux";
import { ResponseAnswerResult } from "@admin/modules/order-fraud/ts/types/response";

export const changeResultAnswer = (
  state: FraudCheckData,
  field: string,
  value: boolean
): FraudCheckData => ({
  ...state,
  resultChange: { ...state.resultChange, [field]: value },
});
export const changeScoreResult = (
  state: FraudCheckData,
  data: ResponseAnswerResult
): FraudCheckData => {
  return {
    ...state,
    answer: {
      ...state.answer,
      diagonal: state.answer.diagonal.map((answer) => {
        const itemChange = data.answerList.find(
          (item) => item.question_code === answer.question_code
        );
        if (itemChange) {
          return { ...answer, ...itemChange };
        }
        return answer;
      }),
      red_flags: state.answer.red_flags.map((answer) => {
        const itemChange = data.answerList.find(
          (item) => item.question_code === answer.question_code
        );
        if (itemChange) {
          return { ...answer, ...itemChange };
        }
        return answer;
      }),
    },
    orderInfo: {
      ...state.orderInfo,
      bareResult: data.bareResult,
      overallResult: data.overallResult,
    },
  };
};
