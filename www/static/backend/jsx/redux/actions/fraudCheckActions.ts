import { FAAnswer } from "@admin/modules/order-fraud/ts/types/answer";

export const setFraudCheckOrderId = (orderId: number) => ({
  type: "SET_ORDER_ID",
  orderId,
});
export const fetchStartCheckData = (orderId: number) => ({
  type: "FETCH_START_CHECK_DATA",
  orderId,
});
export const fetchForceFraudCheck = (orderId: number) => ({
  type: "FETCH_FORCE_FRAUD_CHECK",
  orderId,
});
export const unlockOrder = (orderId: number, all = false) => ({
  type: "UNLOCK_ORDER",
  all,
  orderId,
});
export const changeFraudCheckResult = (formChange: string) => ({
  type: "UPDATE_SCORE_RESULT",
  formChange,
});
export const changeAnswerResult = (
  event: React.ChangeEvent<HTMLInputElement>
) => ({
  type: "CHANGE_RESULT_ANSWER",
  field: event.target.dataset.field ?? null,
  value: event.target.value,
});
export const setTemplateView = (template: FAAnswer) => ({
  type: "SET_TEMPLATE_VIEW",
  template,
});
export const changeFraudCheckStatus = (code: string) => ({
  type: "CHANGE_FRAUD_CHECK_STATUS",
  code,
});
export const updateFraudCheckStatus = (orderId: number, code: string) => ({
  type: "UPDATE_FRAUD_CHECK_STATUS",
  updateData: { orderId, code },
});
