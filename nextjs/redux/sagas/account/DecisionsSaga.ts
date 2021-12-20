import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { route } from "@utils/AppData";

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
  const { success, orderId } = action.payload;

  yield api
    .get<any>(route("order:api:get-eta-products", orderId))
    .then(function (res) {
      success(res);
    });
}

function* uploadLicense(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* payOrder(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* approveIncreaseInShippingChargeDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* cancelOrderDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}
function* checkSentDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>("/order/api/decisions/make-license", data)
    .then(function (res) {
      success(res);
    });
}

function* iSentOriginalPurchaseOrderViaFaxDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* uploadOriginalPurchaseorderDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* sentAchTransferDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* payOrderByCardDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

function* payOrderByPaypalDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:make-license"), data)
    .then(function (res) {
      success(res);
    });
}

export default function* ratingsActionWatcher(): SagaIterator {
  yield takeLatest("SOLVE_DECISION", resolveDecision);
  yield takeLatest("LOAD_MORE_DECISION", loadMore);
  yield takeLatest("UPLOAD_LICENSE_DECISION", uploadLicense);
  yield takeLatest("GET_ETA_PRODUCTS_DECISION", getEtaProductsDecision);
  yield takeLatest("PAY_ORDER_DECISION", payOrder);
  yield takeLatest(
    "APPROVE_INCREASE_IN_SHIPPING_CHARGE_DECISION",
    approveIncreaseInShippingChargeDecision
  );
  yield takeLatest("CANCEL_ORDER_DECISION", cancelOrderDecision);
  yield takeLatest("CHECK_SENT_DECISION", checkSentDecision);
  yield takeLatest(
    "UPLOAD_ORIGINAL_PURCHASE_ORDER_DECISION",
    uploadOriginalPurchaseorderDecision
  );
  yield takeLatest(
    "I_SENT_ORIGINAL_PURCHASE_ORDER_VIA_FAX_DECISION",
    iSentOriginalPurchaseOrderViaFaxDecision
  );
  yield takeLatest("SENT_ACH_TRANSFER_DECISION", sentAchTransferDecision);
  yield takeLatest("PAY_ORDER_BY_CARD_DECISION", payOrderByCardDecision);
  yield takeLatest("PAY_ORDER_BY_PAYPAL_DECISION", payOrderByPaypalDecision);
}
