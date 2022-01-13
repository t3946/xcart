import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { route } from "@utils/AppData";
import axios from "axios";

const api = new ApiService();

function* resolveDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>(route("order:api:decisions_make"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });
}

function* getDecisions(action: any): Generator {
  const { success, data } = action.payload;

  yield axios.post("/api-client/decisions/get", data).then(success);
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

function* submitResponsibilityForCustomDutiesDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>("/submit-responsibility-for-custom-duties", data)
    .then(function (res) {
      success(res);
    });
}

function* formAnswersLTLFreightShipmentDecision(action): Generator {
  const { success, data } = action.payload;

  yield api.post<any>("/send-answers-for-ltl-form", data).then(function (res) {
    success(res);
  });
}

function* poAdditionalInformationRequiredDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>("/po-additional-information-required", data)
    .then(function (res) {
      success(res);
    });
}

function* submitAlternativeItemsOfferDecision(action): Generator {
  const { success, data } = action.payload;

  yield api
    .post<any>("/submit-alternative-items-offer", data)
    .then(function (res) {
      success(res);
    });
}

export default function* ratingsActionWatcher(): SagaIterator {
  yield takeLatest("SOLVE_DECISION", resolveDecision);
  yield takeLatest("GET_DECISIONS", getDecisions);
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
  yield takeLatest(
    "SUBMIT_RESPONSIBILITY_FOR_CUSTOM_DUTIES_DECISION",
    submitResponsibilityForCustomDutiesDecision
  );
  yield takeLatest(
    "FORM_ANSWERS_LTL_FREIGHT_SHIPMENT_DECISION",
    formAnswersLTLFreightShipmentDecision
  );
  yield takeLatest(
    "PO_ADDITIONAL_INFORMATION_REQUIRED_DECISION",
    poAdditionalInformationRequiredDecision
  );
  yield takeLatest(
    "SUBMIT_ALTERNATIVE_ITEMS_OFFER_DECISION",
    submitAlternativeItemsOfferDecision
  );
}
