import { takeLatest } from "redux-saga/effects";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* sendOneTimePassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  yield axios.post("/api-client/user/send-otp", form).then((res) => {
    res.data.error ? error(res.data.error) : success(res);

    complete && complete();

    return res;
  });
}

function* verifyOneTimePassword(action: AnyAction) {
  const { form, success, complete } = action.payload;

  yield axios.post("/api-client/user/verify-otp", form).then((res) => {
    success(res);
    complete(res);

    return res;
  });
}

function* resetPassword(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  yield axios.post("/api-client/user/reset-password", form).then((res) => {
    res.data.errors ? error(res.data.errors) : success(res);

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
