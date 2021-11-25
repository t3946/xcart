import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* resolveDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:decisions_make"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });
}

function* loadMore(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:decisions_get"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });
}

function* getEtaProductsDecision(action): Generator {
  const { success, orderId, data } = action.payload;

  yield api
    .get<any>(route("order:api:get-eta-products", orderId))
    .then(function (res) {
      success(res);
    });
}

export default function* ratingsActionWatcher(): SagaIterator {
  yield takeLatest("SOLVE_DECISION", resolveDecision);
  yield takeLatest("LOAD_MORE_DECISION", loadMore);
  yield takeLatest("GET_ETA_PRODUCTS_DECISION", getEtaProductsDecision);
}
