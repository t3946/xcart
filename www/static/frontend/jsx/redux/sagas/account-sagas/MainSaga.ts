import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { walletActionWatcher } from "./WalletSaga";
import profileActionWatcher from "./ProfileSaga";
import loginAndSecuritySaga from "@client/jsx/redux/sagas/account-sagas/LoginAndSecuritySaga";
import TSVSaga from "@client/jsx/redux/sagas/account-sagas/TSVSaga";

export default function* accountRootSaga(): Generator {
  yield all([
    addressesActionWatcher(),
    authorizationActionWatcher(),
    sharedActionWatcher(),
    walletActionWatcher(),
    profileActionWatcher(),
    loginAndSecuritySaga(),
    TSVSaga(),
  ]);
}
