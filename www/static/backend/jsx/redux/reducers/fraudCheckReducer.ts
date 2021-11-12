import { AnyAction } from "redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { initialFraudCheckStore } from "@admin/modules/order-fraud/ts/consts/initial";
import {
  changeResultAnswer,
  changeScoreResult,
} from "@admin/modules/order-fraud/utils/change-result-answer";

const fraudCheckReducer = (
  state: FraudCheckStore = initialFraudCheckStore,
  action: AnyAction
): FraudCheckStore => {
  switch (action.type) {
    case "SET_TEMPLATE_VIEW":
      return { ...state, templateView: action.template };
    case "SET_ORDER_ID":
      return { ...state, orderId: action.orderId };
    case "SET_NO_CHECK":
      return { ...state, noCheck: true, loading: false };
    case "SET_ALERT_ERROR":
      return {
        ...state,
        alert: { state: true, message: action.message, status: "error" },
      };
    case "CLEAR_ALERT":
      return {
        ...state,
        alert: { state: false, message: "", status: "success" },
      };
    case "SET_SUCCESS_FORCE":
      return { ...state, noCheck: null };
    case "SET_CHECK_DATA":
      return {
        ...state,
        data: action.data,
        loading: false,
        noCheck: null,
      };
    case "CHANGE_RESULT_ANSWER":
      return {
        ...state,
        data: changeResultAnswer(state.data, action.field, action.value),
      };
    case "SET_UNLOCK_ORDER":
      return {
        ...state,
        data: {
          ...state.data,
          settings: {
            ...state.data.settings,
            lock: false,
            locked_orders: false,
          },
        },
      };
    case "SET_FRAUD_CHECK_STATUS":
      return {
        ...state,
        data: {
          ...state.data,
          orderInfo: { ...state.data.orderInfo, fraudStatus: action.newStatus },
        },
        alert: {
          state: true,
          status: "success",
          message: "You have successfully updated data",
        },
      };
    case "CHANGE_FRAUD_CHECK_STATUS":
      return {
        ...state,
        data: {
          ...state.data,
          orderInfo: {
            ...state.data.orderInfo,
            fraudStatus: {
              ...state.data.orderInfo.fraudStatus,
              code: action.code,
            },
          },
        },
      };
    case "FETCH_FORCE_FRAUD_CHECK":
      return { ...state, loading: true };
    case "SET_SCORE_RESULT":
      return {
        ...state,
        data: changeScoreResult(state.data, action.data),
        alert: {
          status: "success",
          message: "You have successfully updated data",
          state: true,
        },
      };
    default:
      return state;
  }
};
export default fraudCheckReducer;
