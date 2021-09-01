import { all, put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { AnyAction } from "redux";
import { ApiService } from "@admin/modules/shared/services/api.service";
import { changeFraudSettingsForm } from "@redux/actions/fraudSettingsActions";

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
  // const form = yield api
  //   .post("/api/fraud-check/settings/update", JSON.stringify(action.form))
  //   .then((response) => response);
  yield put({
    type: "SET_SETTINGS_FORM",
    form: action.form,
  });
}

function* actionWatcher(): SagaIterator {
  yield takeLatest("SET_SETTINGS", setSettings);
  yield takeLatest("CHANGE_SETTINGS_FORM", changeSettingsForm);
}

export default function* rootSaga(): Generator {
  yield all([actionWatcher()]);
}
