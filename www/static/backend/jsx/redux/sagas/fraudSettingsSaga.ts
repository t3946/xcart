import { all, put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { AnyAction } from "redux";
import { ApiService } from "@admin/modules/shared/services/api.service";

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
  const data = yield api
    .post("/api/fraud-check/settings/update", JSON.stringify(action.form))
    .then((response) => response);
  yield put({
    type: "SET_SETTINGS_FORM",
    form: action.form,
  });
}
function* changeFAQuestionData(action: AnyAction): Generator {
  const data = yield api
    .post(`/api/question/fa/update`, JSON.stringify(action.question))
    .then((response) => response) || { update: false };
  yield put({
    type: "SET_FA_QUESTION_DATA",
    question: action.question,
  });
}
function* changeBaseQuestionData(action: AnyAction): Generator {
  const data = yield api
    .post(`/api/question/base/update`, JSON.stringify(action.question))
    .then((response) => response) || { update: false };
  yield put({
    type: "SET_BASE_QUESTION_DATA",
    question: action.question,
  });
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
