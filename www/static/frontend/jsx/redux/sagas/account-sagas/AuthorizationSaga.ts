import { takeLatest, all } from "redux-saga/effects";
import { ApiService } from "../../../modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";

const api = new ApiService();

function* register(action: AnyAction) {
  const { form, callback } = action.payload;
  const postData = {
    RegistrationForm: form,
  };

  yield api
    .post<any>(`/account/api/authorization/register`, JSON.stringify(postData))
    .then((response) => {
      callback();
      return response;
    });
}

function* login(action: AnyAction) {
  const { form, callback } = action.payload;
  const postData = {
    LoginForm: form,
  };

  yield api
    .post<any>(`/account/api/authorization/login`, JSON.stringify(postData))
    .then((response) => {
      callback();
      return response;
    });
}

function* logout() {
  yield api
    .get<any>(`/account/api/authorization/logout`)
    .then((response) => response);
}

function* authorizationActionWatcher(): SagaIterator {
  yield takeLatest("ACCOUNT_REGISTER", register);
  yield takeLatest("ACCOUNT_LOGIN", login);
  yield takeLatest("ACCOUNT_LOGOUT", logout);
}

export default authorizationActionWatcher;
