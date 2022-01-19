import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import Store from "@redux/stores/Store";
import axios from "axios";

const api = new ApiService();

function* getCards(action: AnyAction): Generator {
  const date = Store.getState().ordersStore.selectDate.value;
  const orders = yield api
    .get(`/api/account/orders/get/${action.ordersType}/${date}`)
    .then((response) => response);
  yield put({
    type: "SET_ORDERS",
    orders,
  });
}

function* sendEmail(action: AnyAction): Generator {
  try {
    const formData = new FormData();

    Object.entries(action.email).forEach(([key, value]: any) => {
      if (Array.isArray(value)) {
        value.forEach((e) => {
          formData.append(`${key}[]`, e);
        });
        return;
      }
      if (key === "date" && value) {
        value = value.getTime() / 1000;
      }
      formData.append(key, value);
    });

    yield api.post(`/admin/forms/api/send-email`, formData);

    yield action.onSend();

    yield put({
      type: "STOP_LOADING",
    });
  } catch (e) {}
}

function* fetchOrder(action: AnyAction): Generator {
  const order = yield api
    .get(`/api/account/orders/get-one/${action.orderId}`)
    .then((response) => response);
  yield put({
    type: "SET_ORDER_VIEW",
    order,
  });
}

function* openRMARequest(action: AnyAction): Generator {
  const { data, success } = action;

  yield axios.post("/api/account/orders/open-rma-request", data).then(success);
}

export function* ordersActionWatcher(): SagaIterator {
  yield takeLatest("GET_ORDERS", getCards);
  yield takeLatest("SEND_EMAIL", sendEmail);
  yield takeLatest("FETCH_ORDER_VIEW", fetchOrder);
  yield takeLatest("OPEN_RMA_REQUEST", openRMARequest);
}
