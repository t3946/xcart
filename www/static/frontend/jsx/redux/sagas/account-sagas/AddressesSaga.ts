import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "../../../modules/shared/services/api.service";
import { AnyAction } from "redux";

const api = new ApiService();

function* getAddresses(): Generator {
  const addresses: any = yield api
    .get<any>(`/account/api/addresses/get-addresses`)
    .then((response) => response);

  yield put({
    type: "SET_ADDRESSES",
    addresses,
  });
}

function* changeDefaultAddress(action: AnyAction): Generator {
  const addresses: any = yield api
    .post<any>(`/account/api/addresses/change-default-address`, action.id)
    .then((response) => response);

  yield put({
    type: "SET_ADDRESSES",
    addresses,
  });
}

function* removeAddress(action: AnyAction): Generator {
  const addresses: any = yield api
    .post<any>(`/account/api/addresses/remove-address`, action.id)
    .then((response) => response);

  yield put({
    type: "SET_ADDRESSES",
    addresses,
  });
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_ADDRESSES", getAddresses);
  yield takeLatest("CHANGE_DEFAULT_ADDRESS", changeDefaultAddress);
  yield takeLatest("REMOVE_ADDRESS", removeAddress);
}
