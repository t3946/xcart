import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import Store from "@redux/stores/Store";

const api = new ApiService();

function* getCards(action: AnyAction): Generator {
  const orders: any = yield api
    .get<any>(
      `/api/account/orders/get-orders/${action.ordersType}/${
        Store.getState().ordersStore.orders[action.ordersType].selectValue.value
      }`
    )
    .then((response) => response);

  yield put({
    type: "SET_ORDERS",
    orders: orders.data,
    orderType: action.ordersType,
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

export function* ordersActionWatcher(): SagaIterator {
  yield takeLatest("GET_ORDERS", getCards);
  yield takeLatest("SEND_EMAIL", sendEmail);
}
