import { put, takeLatest } from "redux-saga/effects";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* register(action: AnyAction) {
  const { data, success } = action.payload;

  yield axios.post("/api-client/user/create", data).then(success);
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
  const { success, error } = action.payload;

  yield put({
    type: "USER_CLEAR",
  });

  yield put({
    type: "SET_LISTS",
    lists: null,
  });

  yield axios.get("/api-client/user/logout").then(success).catch(error);
}

function* authorizationActionWatcher(): SagaIterator {
  yield takeLatest("ACCOUNT_REGISTER", register);
  yield takeLatest("ACCOUNT_LOGIN", login);
  yield takeLatest("ACCOUNT_CHECK_USER_LOGIN", checkUserLogin);
  yield takeLatest("ACCOUNT_LOGOUT", logout);
}

export default authorizationActionWatcher;
