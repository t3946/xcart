import { all, put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { AnyAction } from "redux";
import { ApiService } from "@admin/modules/shared/services/api.service";
import axios from "axios";

const api = new ApiService();

function* setSettings(action: AnyAction): Generator {
  const settingsList: any = yield api
    .get<any>(`/api/fraud-check/settings/all`)
    .then((response) => response);

  yield put({
    type: "SET_FRAUD_SETTINGS",
    data: settingsList,
  });
}

function* changeSettingsForm(action: AnyAction): Generator {
  try {
    const data = yield axios
      .post("/api/fraud-check/settings/update", JSON.stringify(action.form))
      .then((response) => response);
    yield put({
      type: "SET_SUCCESS_ALERT",
      message: "You have successfully updated data",
    });
  } catch (e) {
    yield put({
      type: "SET_ERROR_ALERT",
      message: e.response.data.message,
    });
  }
}
function* changeFAQuestionData(action: AnyAction): Generator {
  try {
    yield axios
      .post(`/api/question/fa/update`, JSON.stringify(action.question))
      .then((response) => response);
    yield put({
      type: "SET_FA_QUESTION_DATA",
      question: action.question,
    });
    yield put({
      type: "SET_SUCCESS_ALERT",
      message: "You have successfully updated data",
    });
  } catch (e) {
    yield put({
      type: "SET_ERROR_ALERT",
      message: e.response.data.message,
    });
  }
}
function* changeBaseQuestionData(action: AnyAction): Generator {
  try {
    const data = yield axios
      .post(`/api/question/base/update`, JSON.stringify(action.question))
      .then((response) => response.data);
    yield put({
      type: "SET_BASE_QUESTION_DATA",
      question: action.question,
    });
    yield put({
      type: "SET_SUCCESS_ALERT",
      message: "You have successfully updated data",
    });
  } catch (e) {
    yield put({
      type: "SET_ERROR_ALERT",
      message: e.response.data.message,
    });
  }
}

function* actionWatcher(): SagaIterator {
  yield takeLatest("SET_SETTINGS", setSettings);
  yield takeLatest("CHANGE_SETTINGS_FORM", changeSettingsForm);
  yield takeLatest("CHANGE_FA_QUESTION_DATA", changeFAQuestionData);
  yield takeLatest("CHANGE_BASE_QUESTION_DATA", changeBaseQuestionData);
}

export default function* rootSaga(): Generator {
  yield all([actionWatcher()]);
}
