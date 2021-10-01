import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { paymentsActionWatcher } from "./PaymentsSaga";
import profileActionWatcher from "./ProfileSaga";
import loginAndSecuritySaga from "@client/jsx/redux/sagas/account-sagas/LoginAndSecuritySaga";
import { listsActionWatcher } from "@client/jsx/redux/sagas/account-sagas/ListsSaga";
import TSVSaga from "@client/jsx/redux/sagas/account-sagas/TSVSaga";
import ResetPasswordSaga from "@client/jsx/redux/sagas/account-sagas/ResetPasswordSaga";
import ratingsActionWatcher from "@client/jsx/redux/sagas/account-sagas/RatingsSaga";

export default function* accountRootSaga(): Generator {
  yield all([
    addressesActionWatcher(),
    authorizationActionWatcher(),
    sharedActionWatcher(),
    paymentsActionWatcher(),
    profileActionWatcher(),
    loginAndSecuritySaga(),
    listsActionWatcher(),
    TSVSaga(),
    ResetPasswordSaga(),
    ratingsActionWatcher(),
  ]);
}
