import { takeLatest } from "redux-saga/effects";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* send(action: AnyAction) {
  const { data, success } = action.payload;

  yield axios.post<any>("/api-client/user/send-feedback", data).then(success);
}

function* profileActionWatcher(): SagaIterator {
  yield takeLatest("SEND_FEEDBACK", send);
}

export default profileActionWatcher;
