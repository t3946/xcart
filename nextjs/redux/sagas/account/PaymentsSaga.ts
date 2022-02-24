import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import Store from "@redux/stores/Store";
import axios from "axios";

const api = new ApiService();

const getUser = () => {
  return Store.getState().user;
};

function* getCards(): Generator {
  const cards: any = yield api
    .get<any>(`/api/account/wallet/get-cards`)
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });
}

function* editCard(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(
      `/api/account/wallet/edit-card`,
      JSON.stringify({ ...action.cardInfo, user: getUser().id })
    )
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });

  yield put({
    type: "GET_ADDRESSES",
    userId: Store.getState().user.id,
  });

  yield action.onRequestEnd();
}

function* removeCard(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(
      `/api/account/wallet/remove-card`,
      JSON.stringify({
        user: Store.getState().user.id,
        card: action.id,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });

  yield action.onRequestEnd();
}

function* getTransactions(): Generator {
  const transactions: any = yield api
    .get<any>(`/api/account/wallet/get-transactions`)
    .then((response) => response);

  yield put({
    type: "SET_TRANSACTIONS",
    transactions,
  });
}

function* changeDefaultCard(action: any): Generator {
  const { data, success } = action.payload;

  yield axios
    .post("/api-client/user/stripe/customer/change-default-source", data)
    .then(success);
}

function* addCard(action: any): Generator {
  const { data, success } = action.payload;

  yield axios.post("/api-client/user/stripe/card/create", data).then(success);
}

function* deleteCard(action: any): Generator {
  const { data, success } = action.payload;

  yield axios.post("/api-client/user/stripe/card/delete", data).then(success);
}

function* changeAddressCard(action: any): Generator {
  const { addressId, cardId, success } = action.payload;

  yield axios
    .post("/api-client/user/stripe/customer/update-source", {
      params: {
        metadata: {
          addressId,
        },
      },
      cardId: cardId,
    })
    .then(success)
    .catch();
}

function* changeCardHolderName(action: any): Generator {
  const { cardHolderName, cardId, success } = action.payload;

  yield axios
    .post("/api-client/user/stripe/customer/update-source", {
      params: {
        metadata: {
          cardHolderName,
        },
      },
      cardId: cardId,
    })
    .then(success)
    .catch();
}

export function* paymentsActionWatcher(): SagaIterator {
  yield takeLatest("GET_CARDS", getCards);
  yield takeLatest("EDIT_CARD", editCard);
  yield takeLatest("REMOVE_CARD", removeCard);
  yield takeLatest("GET_TRANSACTIONS", getTransactions);
  yield takeLatest("CHANGE_DEFAULT_CARD", changeDefaultCard);
  yield takeLatest("ADD_CARD_SAGA", addCard);
  yield takeLatest("DELETE_CARD", deleteCard);
  yield takeLatest("CHANGE_ADDRESS_CARD", changeAddressCard);
  yield takeLatest("CHANGE_CARDHOLDER_NAME", changeCardHolderName);
}
