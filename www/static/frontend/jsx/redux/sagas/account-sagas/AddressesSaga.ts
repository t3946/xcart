import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";

const api = new ApiService();

function* getAddresses(): Generator {
  const result: any = yield api
    .get<any>(`/account/api/addresses/get-addresses`)
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result.addresses,
      countries: result.countries,
      states: result.states,
    });
  } catch (error) {
    console.log(error);
  }
}

function* changeDefaultAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(`/account/api/addresses/change-default-address`, action.id)
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result.addresses,
      countries: result.countries,
      states: result.states,
    });
  } catch (error) {
    console.log(error);
  }
}

function* removeAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(`/account/api/addresses/remove-address`, action.id)
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result.addresses,
      countries: result.countries,
      states: result.states,
    });
  } catch (error) {
    console.log(error);
  }
}

function* addAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/account/api/addresses/add-address`,
      JSON.stringify(action.address)
    )
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result.addresses,
      countries: result.countries,
      states: result.states,
    });
  } catch (error) {
    console.log(error);
    return;
  }

  yield action.onPendingEnd();
}

function* editAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/account/api/addresses/edit-address`,
      JSON.stringify(action.address)
    )
    .then((response) => response)
    .catch((error) => console.log(error));

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result.addresses,
      countries: result.countries,
      states: result.states,
    });
  } catch (error) {
    console.log(error);
  }

  yield action.onPendingEnd();
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_ADDRESSES", getAddresses);
  yield takeLatest("CHANGE_DEFAULT_ADDRESS", changeDefaultAddress);
  yield takeLatest("REMOVE_ADDRESS", removeAddress);
  yield takeLatest("ADD_ADDRESS", addAddress);
  yield takeLatest("EDIT_ADDRESS", editAddress);
}
