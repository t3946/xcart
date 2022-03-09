import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";

const api = new ApiService();

function* savePublicProfile(action: AnyAction) {
  const { data, success, error, complete } = action.payload;

  yield api
    .post<any>("/api/account/profile/save-pubic-profile", data)
    .then((res) => {
      res.errors ? error(res) : success(res);
      complete();
      return res;
    });
}

function* profileActionWatcher(): SagaIterator {
  yield takeLatest("ACCOUNT_SAVE_PUBLIC_PROFILE", savePublicProfile);
}

export default profileActionWatcher;
