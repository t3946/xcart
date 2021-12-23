import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import { route } from "@utils/AppData";

const api = new ApiService();

function* editName(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    EditNameForm: form,
  });

  yield api.post<any>(route("account:api:edit-name"), data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* editEmail(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    EditEmailForm: form,
  });

  yield api.post<any>(route("account:api:edit-email"), data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* editPhone(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    EditPhoneForm: form,
  });

  yield api.post<any>(route("account:api:edit-phone"), data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* changePassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    ChangePasswordForm: form,
  });

  yield api
    .post<any>("/api/account/login-and-security/edit-password", data)
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

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
