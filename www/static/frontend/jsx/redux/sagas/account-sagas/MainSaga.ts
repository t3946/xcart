import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { walletActionWatcher } from "./WalletSaga";
import profileActionWatcher from "./ProfileSaga";

export default function* accountRootSaga(): Generator {
  yield all([
    addressesActionWatcher(),
    authorizationActionWatcher(),
    sharedActionWatcher(),
    walletActionWatcher(),
    profileActionWatcher(),
  ]);
}
