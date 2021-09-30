import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";

const api = new ApiService();

function* getProductRatings(action): Generator {
  const result: any = yield api
    .post<any>(`/account/api/addresses/get-addresses`, action.userId)
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result,
    });
  } catch (error) {
    console.log(error);
  }
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_PRODUCT_RATINGS", getProductRatings);
}
