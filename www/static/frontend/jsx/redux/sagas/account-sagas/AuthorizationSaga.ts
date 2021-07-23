import { takeLatest, all } from "redux-saga/effects";
import { ApiService } from "../../../modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";

const api = new ApiService();

function* register(action: AnyAction) {
  console.log("Saga generator register", action);

  yield api
    .post<any>(`/account/register/`, JSON.stringify(action.registerForm))
    .then((response) => response);
}

function* login(action: AnyAction) {
  console.log("Saga generator login", action);

  const { form, callback } = action.payload;
  const postData = {
    LoginForm: form,
  };

  yield api
    .post<any>(`/account/login/`, JSON.stringify(postData))
    .then((response) => {
      callback();
      return response;
    });
}

function* logout(action: AnyAction) {
  console.log("Saga generator logout", action);

  yield api
    .post<any>(`/account/logout/`, JSON.stringify(action.logoutForm))
    .then((response) => response);
}

function* authorizationActionWatcher(): SagaIterator {
  yield takeLatest("ACCOUNT_REGISTER", register);
  yield takeLatest("ACCOUNT_LOGIN", login);
  yield takeLatest("ACCOUNT_LOGOUT", logout);
}

export default authorizationActionWatcher;
