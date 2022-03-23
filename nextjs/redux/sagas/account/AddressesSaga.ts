import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import Store from "@redux/stores/Store";
import axios from "axios";

const api = new ApiService();

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
  const result: any = yield api
    .post<any>(
      `/api/account/addresses/change-default-address`,
      JSON.stringify({
        user: action.userId,
        addressId: action.id,
      })
    )
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

  action.callback();
}

function* removeAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/api/account/addresses/remove-address`,
      JSON.stringify({
        addressId: action.id,
        user: Store.getState().user.id,
      })
    )
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

  action.callback();
}

function* addAddress(action: AnyAction): Generator {
  const result: any = yield api.post<any>(
    `/api/account/addresses/add-address`,
    JSON.stringify({
      user: action.userId,
      address: action.address,
    })
  );

  const oldList = Store.getState().addresses.addressesList;

  const newBilling = result.find(
    (newAddress) =>
      !oldList.find(
        (oldAddress) => oldAddress.address_id === newAddress.address_id
      )
  );

  try {
    yield put({
      type: "SET_ADDRESSES",
      addresses: result,
    });
  } catch (error) {
    console.log(error);
    return;
  }

  if (action.onPendingEnd) {
    yield action.onPendingEnd(newBilling);
  }
}

function* editAddress(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/api/account/addresses/edit-address`,
      JSON.stringify({
        address: action.address,
        user: Store.getState().user.id,
      })
    )
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

  yield action.onPendingEnd();
}

export function* addressesActionWatcher(): SagaIterator {
  yield takeLatest("GET_ADDRESSES", getAddresses);
  yield takeLatest("CHANGE_DEFAULT_ADDRESS", changeDefaultAddress);
  yield takeLatest("REMOVE_ADDRESS", removeAddress);
  yield takeLatest("ADD_ADDRESS", addAddress);
  yield takeLatest("EDIT_ADDRESS", editAddress);
}
