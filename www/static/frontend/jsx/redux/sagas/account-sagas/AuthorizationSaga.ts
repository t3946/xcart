import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* register(action: AnyAction) {
  const { form, success, error, complete } = action.payload;
  const postData = {
    RegistrationForm: form,
  };

  yield api
    .post(`/account/api/authorization/register`, JSON.stringify(postData))
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

      complete(res);

      return res;
    });
}

function* login(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    LoginForm: form,
  });

  yield api.post(`/account/api/authorization/login`, data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* checkUserLogin(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    LoginForm: form,
  });

  yield api
    .post(route("account:authorization_api:check-login"), data)
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

      complete();

      return res;
    });
}

function* logout(action: AnyAction) {
  const { callback } = action.payload;

  yield api.get(route("account:authorization_api:logout")).then((response) => {
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
