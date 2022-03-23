import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { AnyAction } from "redux";
import axios from "axios";

function* getAddresses(): Generator {
  let addresses = null;

  yield axios.get<any>(`/api-client/user/address/get`).then((res) => {
    addresses = res.data.addresses;
  });

  yield put({
    type: "SET_ADDRESSES",
    addresses: addresses,
  });
}

function* changeDefaultAddress(action: AnyAction): Generator {
  yield axios.post("/api-client/user/address/set-default", {
    addressId: action.id,
  });

  yield getAddresses();

  action.callback();
}

function* removeAddress(action: AnyAction): Generator {
  yield axios.post("/api-client/user/address/remove", {
    addressId: action.id,
  });

  yield getAddresses();

  action.callback();
}

function* addAddress(action: AnyAction): Generator {
  let newAddress = null;

  yield axios
    .post("/api-client/user/address/create", {
      address: action.address,
    })
    .then((res) => {
      newAddress = res.data.address;
    });

  yield getAddresses();

  if (action.onPendingEnd) {
    yield action.onPendingEnd(newAddress);
  }
}

function* editAddress(action: AnyAction): Generator {
  yield axios.post("/api-client/user/address/edit", {
    address: action.address,
  });

  yield getAddresses();

  yield action.onPendingEnd();
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_ADDRESSES", getAddresses);
  yield takeLatest("ADD_ADDRESS", addAddress);
  yield takeLatest("EDIT_ADDRESS", editAddress);
  yield takeLatest("REMOVE_ADDRESS", removeAddress);
  yield takeLatest("CHANGE_DEFAULT_ADDRESS", changeDefaultAddress);
}
