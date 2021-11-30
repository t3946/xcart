import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { AnyAction } from "redux";
import Store from "@redux/stores/Store";

const api = new ApiService();

const getUser = () => {
  return Store.getState().user;
};

function* getCards(): Generator {
  const cards: any = yield api
    .post<any>(`/account/api/wallet/get-cards`, getUser().id)
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });
}

function* changeDefault(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(
      `/account/api/wallet/change-default`,
      JSON.stringify({ cardId: action.id, user: getUser().id })
    )
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });
}

function* addCard(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(
      `/account/api/wallet/add-card`,
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

function* editCard(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(
      `/account/api/wallet/edit-card`,
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
      `/account/api/wallet/remove-card`,
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
    .post<any>(`/account/api/wallet/get-transactions`, getUser().id)
    .then((response) => response);

  yield put({
    type: "SET_TRANSACTIONS",
    transactions,
  });
}

export function* paymentsActionWatcher(): SagaIterator {
  yield takeLatest("GET_CARDS", getCards);
  yield takeLatest("CHANGE_DEFAULT_CARD", changeDefault);
  yield takeLatest("ADD_CARD", addCard);
  yield takeLatest("EDIT_CARD", editCard);
  yield takeLatest("REMOVE_CARD", removeCard);
  yield takeLatest("GET_TRANSACTIONS", getTransactions);
}
