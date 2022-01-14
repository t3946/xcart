import { takeLatest } from "redux-saga/effects";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* confirmCode(action: AnyAction) {
  const { data, success, error, complete } = action.payload;

  yield axios
    .post<any>("/api-client/user/tsv/confirm-code", data)
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

function* TSVSaga(): SagaIterator {
  yield takeLatest("ACCOUNT_TSV_CONFIRM_CODE", confirmCode);
  yield takeLatest("ACCOUNT_TSV_DISABLE", disable);
  yield takeLatest("ACCOUNT_TSV_REQUIRE_FOR_ALL", requireForAll);
}

export default TSVSaga;
