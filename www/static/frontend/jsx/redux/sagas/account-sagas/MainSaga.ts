import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";

export default function* accountRootSaga(): Generator {
  yield all([addressesActionWatcher(), authorizationActionWatcher()]);
}
