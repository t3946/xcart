import { all } from "redux-saga/effects";
import { sharedActionWatcher } from "./SharedSaga";
import { listsActionWatcher } from "@client/jsx/redux/sagas/account/ListsSaga";
import ProductSaga from "@client/jsx/redux/sagas/ProductSaga";
import ReviewSaga from "@client/jsx/redux/sagas/account/ReviewSaga";
import SuggestionSaga from "@client/jsx/redux/sagas/SuggestionSaga";

export default function* accountRootSaga(): Generator {
  yield all([
    sharedActionWatcher(),
    listsActionWatcher(),
    ProductSaga(),
    ReviewSaga(),
    SuggestionSaga(),
  ]);
}
