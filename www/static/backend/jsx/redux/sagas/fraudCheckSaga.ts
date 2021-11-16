import { all, put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { AnyAction } from "redux";
import axios from "axios";
const api = new ApiService();
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

function* fetchBaseCheckData(action: AnyAction): Generator {
  try {
    const data = yield axios
      .get(`/api/order/fraud-check/settings/${action.orderId}`)
      .then((response) => response.data);
    yield put({
      type: "SET_CHECK_DATA",
      data,
    });
  } catch (error) {
    if (error.response.status === 404) {
      yield put({
        type: "SET_NO_CHECK",
      });
    } else {
      yield put({
        type: "SET_ALERT_ERROR",
        message: error.response.data.message,
      });
    }
  }
}
function* forceFraudCheck(action: AnyAction): Generator {
  try {
    /* Первый запрос отправляется для принудительной проверки заказа на fraud check, второй на получение результата */
    yield axios
      .get(`/api/order/fraud-check/force-check/${action.orderId}`)
      .then((response) => response.data);
    const data = yield axios
      .get(`/api/order/fraud-check/settings/${action.orderId}`)
      .then((response) => response.data);

    yield put({
      type: "SET_CHECK_DATA",
      data,
    });
  } catch (e) {
    yield put({
      type: "SET_ALERT_ERROR",
      message: e.response.data.message,
    });
  }
}
function* unlockOrder(action: AnyAction): Generator {
  try {
    const url = action.all
      ? "/api/order/fraud-check/unlock-all"
      : `/api/order/fraud-check/unlock/${action.orderId}`;
    const status = yield axios.get(url).then((response) => response.data);
    yield put({ type: "SET_UNLOCK_ORDER" });
  } catch (e) {}
}
function* changeScoreResult(action: AnyAction): Generator {
  try {
    const data = yield api
      .post("/api/order/fraud-check/change-result", action.formChange)
      .then((response) => response);
    yield put({ type: "SET_SCORE_RESULT", data });
  } catch (e) {}
}
function* updateFraudCheckStatus(action: AnyAction): Generator {
  try {
    const newStatus = yield axios
      .post(
        "/api/order/fraud-status/update",
        JSON.stringify({ orderId: action.orderId, code: action.code })
      )
      .then((response) => response.data);
    yield put({ type: "SET_FRAUD_CHECK_STATUS", newStatus });
  } catch (e) {
    yield put({
      type: "SET_ALERT_ERROR",
      message: e.response.data.message,
    });
  }
}
function* actionWatcher(): SagaIterator {
  yield takeLatest("FETCH_START_CHECK_DATA", fetchBaseCheckData);
  yield takeLatest("FETCH_FORCE_FRAUD_CHECK", forceFraudCheck);
  yield takeLatest("UNLOCK_ORDER", unlockOrder);
  yield takeLatest("UPDATE_SCORE_RESULT", changeScoreResult);
  yield takeLatest("UPDATE_FRAUD_CHECK_STATUS", updateFraudCheckStatus);
}
export default function* fraudCheckSaga(): Generator {
  yield all([actionWatcher()]);
}
