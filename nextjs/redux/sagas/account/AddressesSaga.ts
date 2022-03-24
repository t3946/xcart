import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { AnyAction } from "redux";
import axios from "axios";
import Store from "@redux/stores/Store";

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
  const addresses: any = Store.getState().addresses.addressesList;
  const newAddresses = [];

  for (const address of addresses) {
    address.is_default = address.address_id === action.id;

    if (address.is_default) {
      newAddresses.unshift(address);
    } else {
      newAddresses.push(address);
    }
  }

  yield put({
    type: "SET_ADDRESSES",
    addresses: newAddresses,
  });

  action.callback();

  let error = null;

  yield axios
    .post("/api-client/user/address/set-default", {
      addressId: action.id,
    })
    .catch((err) => {
      error = err;
    });

  if (error) {
    yield getAddresses();
  }
}

function* removeAddress(action: AnyAction): Generator {
  const oldAddresses: any = Store.getState().addresses.addressesList;
  const newAddresses = [];

  for (const address of oldAddresses) {
    if (address.address_id !== action.id) {
      newAddresses.push(address);
    }
  }

  yield put({
    type: "SET_ADDRESSES",
    addresses: newAddresses,
  });

  let error = null;

  action.callback();

  yield axios
    .post("/api-client/user/address/remove", {
      addressId: action.id,
    })
    .catch((err) => {
      error = err;
    });

  if (error) {
    yield getAddresses();
  }
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
  const addresses: any = Store.getState().addresses.addressesList;

  for (const address of addresses) {
    if (address.address_id === action.address.address_id) {
      for (const key in address) {
        address[key] = action.address[key];
      }
    }
  }

  yield put({
    type: "SET_ADDRESSES",
    addresses,
  });

  yield action.onPendingEnd();

  let error = null;

  yield axios
    .post("/api-client/user/address/edit", {
      address: action.address,
    })
    .catch((err) => {
      error = err;
    });

  if (error) {
    yield getAddresses();
  }
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_ADDRESSES", getAddresses);
  yield takeLatest("ADD_ADDRESS", addAddress);
  yield takeLatest("EDIT_ADDRESS", editAddress);
  yield takeLatest("REMOVE_ADDRESS", removeAddress);
  yield takeLatest("CHANGE_DEFAULT_ADDRESS", changeDefaultAddress);
}
