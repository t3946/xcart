import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* sendOneTimePassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  yield api
    .post<any>(
      route("account:api:send-one-time-password"),
      JSON.stringify(form)
    )
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

      complete && complete();

      return res;
    });
}

function* verifyOneTimePassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  yield api
    .post<any>(
      route("account:api:verify-one-time-password"),
      JSON.stringify(form)
    )
    .then((res) => {
      res.errors ? error(res) : success(res);

      complete(res);

      return res;
    });
}

function* resetPassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  yield api
    .post<any>(route("account:api:reset-password"), JSON.stringify(form))
    .then((res) => {
      res.errors ? error(res.errors) : success(res);

      complete && complete();

      return res;
    });
}

function* ResetPasswordSaga(): SagaIterator {
  yield takeLatest("PA_SEND_ONE_TIME_PASSWORD", sendOneTimePassword);
  yield takeLatest("PA_VERIFY_ONE_TIME_PASSWORD", verifyOneTimePassword);
  yield takeLatest("PA_RESET_PASSWORD", resetPassword);
}

export default ResetPasswordSaga;
