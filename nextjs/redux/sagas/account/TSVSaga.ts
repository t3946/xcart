import { takeLatest } from "redux-saga/effects";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* confirmDevice(action: AnyAction) {
  const { data, success, error, complete } = action.payload;

  yield axios
    .post<any>("/api-client/user/tsv/confirm-device", data)
    .then((res) => {
      res.data.errors ? error(res.data.errors) : success(res);

      complete();

      return res;
    });
}

function* disable(action: AnyAction) {
  const { data, success } = action.payload;

  yield axios.get<any>("/api-client/user/tsv/disable", data).then(success);
}

function* requireForAll(action: AnyAction) {
  const { data, success } = action.payload;

  yield axios
    .get<any>("/api-client/user/tsv/require-for-all", data)
    .then(success);
}

function* changePreferredMethod(action: AnyAction) {
  const { data, success } = action.payload;

  yield axios
    .post<any>("/api-client/user/tsv/change-preferred-method", data)
    .then(success);
}

function* TSVSaga(): SagaIterator {
  yield takeLatest("ACCOUNT_TSV_CONFIRM_DEVICE", confirmDevice);
  yield takeLatest("ACCOUNT_TSV_DISABLE", disable);
  yield takeLatest("ACCOUNT_TSV_REQUIRE_FOR_ALL", requireForAll);
  yield takeLatest(
    "ACCOUNT_TSV_CHANGE_PREFERRED_METHOD",
    changePreferredMethod
  );
}

export default TSVSaga;
