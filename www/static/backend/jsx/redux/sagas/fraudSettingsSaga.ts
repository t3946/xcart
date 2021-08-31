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

function* actionWatcher(): SagaIterator {
  yield takeLatest("SET_SETTINGS", setSettings);
}

export default function* rootSaga(): Generator {
  yield all([actionWatcher()]);
}
