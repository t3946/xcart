import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { paymentsActionWatcher } from "./PaymentsSaga";
import profileActionWatcher from "./ProfileSaga";
import loginAndSecuritySaga from "@client/jsx/redux/sagas/account/LoginAndSecuritySaga";
import { listsActionWatcher } from "@client/jsx/redux/sagas/account/ListsSaga";
import TSVSaga from "@client/jsx/redux/sagas/account/TSVSaga";
import ResetPasswordSaga from "@client/jsx/redux/sagas/account/ResetPasswordSaga";
import ProductSaga from "@client/jsx/redux/sagas/ProductSaga";
import ReviewSaga from "@client/jsx/redux/sagas/account/ReviewSaga";
import { ordersActionWatcher } from "@client/jsx/redux/sagas/account/OrdersSaga";
import DecisionsSaga from "@client/jsx/redux/sagas/account/DecisionsSaga";

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
    ordersActionWatcher(),
    ProductSaga(),
    ReviewSaga(),
    DecisionsSaga(),
  ]);
}
