import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";

const api = new ApiService();

function* getCards(action: AnyAction): Generator {
  const orders: any = yield api
    .get<any>(
      `/account/api/orders/get-orders/${action.ordersType}/${
        accountStore.getState().ordersStore.orders[action.ordersType]
          .selectValue.value
      }`
    )
    .then((response) => response);

  yield put({
    type: "SET_ORDERS",
    orders: orders.data,
    orderType: action.ordersType,
  });
}

export function* ordersActionWatcher(): SagaIterator {
  yield takeLatest("GET_ORDERS", getCards);
}
