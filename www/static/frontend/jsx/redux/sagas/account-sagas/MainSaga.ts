import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { paymentsActionWatcher } from "./PaymentsSaga";
import profileActionWatcher from "./ProfileSaga";
import loginAndSecuritySaga from "@client/jsx/redux/sagas/account-sagas/LoginAndSecuritySaga";
import { listsActionWatcher } from "@client/jsx/redux/sagas/account-sagas/ListsSaga";

export default function* accountRootSaga(): Generator {
  yield all([
    addressesActionWatcher(),
    authorizationActionWatcher(),
    sharedActionWatcher(),
    paymentsActionWatcher(),
    profileActionWatcher(),
    loginAndSecuritySaga(),
    listsActionWatcher(),
  ]);
}
