import { all } from "redux-saga/effects";
import { addressesActionWatcher } from "./AddressesSaga";
import authorizationActionWatcher from "./AuthorizationSaga";
import { sharedActionWatcher } from "./SharedSaga";
import { paymentsActionWatcher } from "./PaymentsSaga";
import profileActionWatcher from "./ProfileSaga";
import loginAndSecuritySaga from "@redux/sagas/account/LoginAndSecuritySaga";
import { listsActionWatcher } from "@redux/sagas/account/ListsSaga";
import TSVSaga from "@redux/sagas/account/TSVSaga";
import ResetPasswordSaga from "@redux/sagas/account/ResetPasswordSaga";
import ProductSaga from "@redux/sagas/ProductSaga";
import ReviewSaga from "@redux/sagas/account/ReviewSaga";
import { ordersActionWatcher } from "@redux/sagas/account/OrdersSaga";
import DecisionsSaga from "@redux/sagas/account/DecisionsSaga";
import SuggestionSaga from "@redux/sagas/SuggestionSaga";
import FeedbackSaga from "@redux/sagas/account/FeedbackSaga";
import Cart from "@redux/sagas/Cart";
import axios from "axios";

axios.interceptors.request.use(
  function (config) {
    config.headers = {
      ...config.headers,
      "X-Requested-With": "XMLHttpRequest",
    };
    return config;
  },
  function (error) {
    if (error.response.status === 401) {
      window.location.href = "/account/login";
    }
    return Promise.reject(error);
  }
);

axios.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response.status === 401) {
      window.location.href = "/account/login";
    }
    return Promise.reject(error);
  }
);

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
    SuggestionSaga(),
    FeedbackSaga(),
    Cart(),
  ]);
}
