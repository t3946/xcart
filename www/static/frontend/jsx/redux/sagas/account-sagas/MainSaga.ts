import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";

export default function* accountRootSaga(): Generator {
  yield all([addressesActionWatcher()]);
}
