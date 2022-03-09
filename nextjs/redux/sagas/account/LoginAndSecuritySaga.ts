import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

const api = new ApiService();

function* editName(action: AnyAction) {
  const { data, success, complete } = action.payload;

  yield axios.post("/api-client/user/change-name", data).then((res) => {
    success(res);

    complete();

    return res;
  });
}

function* editEmail(action: AnyAction) {
  const { data, success, complete } = action.payload;

  yield axios.post("/api-client/user/change-email", data).then((res) => {
    success(res);

    complete();

    return res;
  });
}

function* editPhone(action: AnyAction) {
  const { data, success, complete } = action.payload;

  yield axios.post<any>("/api-client/user/change-phone", data).then((res) => {
    success(res);

    complete();

    return res;
  });
}

function* changePassword(action: AnyAction) {
  const { data, success, error, complete } = action.payload;

  yield axios
    .post<any>("/api-client/user/change-password", data)
    .then((res) => {
      res.data.errors ? error(res.data.errors) : success(res);

      complete();

      return res;
    });
}

function* loginAndSecuritySaga(): SagaIterator {
  yield takeLatest("ACCOUNT_EDIT_NAME", editName);
  yield takeLatest("ACCOUNT_EDIT_EMAIL", editEmail);
  yield takeLatest("ACCOUNT_EDIT_PHONE", editPhone);
  yield takeLatest("ACCOUNT_EDIT_PASSWORD", changePassword);
}

export default loginAndSecuritySaga;
