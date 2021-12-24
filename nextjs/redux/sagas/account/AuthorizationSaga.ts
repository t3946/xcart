import { put, takeLatest } from "redux-saga/effects";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

const api = new ApiService();

function* register(action: AnyAction) {
  const { form, success, error, complete } = action.payload;
  const postData = {
    RegistrationForm: form,
  };

  yield api
    .post<any>(`/api/account/authorization/register`, JSON.stringify(postData))
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

      complete(res);

      return res;
    });
}

function* login(action: AnyAction) {
  const { form, success, complete } = action.payload;

  yield axios
    .post("/api-client/user/login", form)
    .then(success)
    .finally(complete);
}

function* checkUserLogin(action: AnyAction) {
  const { data, success, complete } = action.payload;

  yield axios
    .post("/api-client/user/check-login", data)
    .then(success)
    .finally(complete);
}

function* logout(action: AnyAction) {
  const { callback } = action.payload;

  yield put({
    type: "USER_CLEAR",
  });

  yield put({
    type: "SET_LISTS",
    lists: null,
  });

  yield axios.get("/api-client/user/logout").then((response) => {
    callback();
    return response;
  });
}

function* authorizationActionWatcher(): SagaIterator {
  yield takeLatest("ACCOUNT_REGISTER", register);
  yield takeLatest("ACCOUNT_LOGIN", login);
  yield takeLatest("ACCOUNT_CHECK_USER_LOGIN", checkUserLogin);
  yield takeLatest("ACCOUNT_LOGOUT", logout);
}

export default authorizationActionWatcher;
