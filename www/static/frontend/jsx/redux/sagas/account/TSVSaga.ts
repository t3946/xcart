import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* confirmCode(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify(form);

  yield api.post<any>(route("account:api:confirm-code"), data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* disable(action: AnyAction) {
  const { form, success } = action.payload;

  const data = JSON.stringify(form);

  yield api.post<any>(route("account:api:disable"), data).then((res) => {
    success(res);

    return res;
  });
}

function* TSVSaga(): SagaIterator {
  yield takeLatest("ACCOUNT_TSV_CONFIRM_CODE", confirmCode);
  yield takeLatest("ACCOUNT_TSV_DISABLE", disable);
}

export default TSVSaga;
