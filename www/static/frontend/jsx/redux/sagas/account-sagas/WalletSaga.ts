import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "../../../modules/shared/services/api.service";
import { AnyAction } from "redux";

const api = new ApiService();

function* getCards(): Generator {
  const cards: any = yield api
    .get<any>(`/account/api/wallet/get-cards`)
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });
}

function* changeDefault(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(`/account/api/wallet/change-default`, action.id)
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });
}

function* addCard(action: AnyAction): Generator {
  const cards: any = yield api
    .post<any>(`/account/api/wallet/add-card`, JSON.stringify(action.cardInfo))
    .then((response) => response);

  yield put({
    type: "SET_CARDS",
    cards,
  });

  yield action.onRequestEnd();
}

export function* walletActionWatcher(): SagaIterator {
  yield takeLatest("GET_CARDS", getCards);
  yield takeLatest("CHANGE_DEFAULT_CARD", changeDefault);
  yield takeLatest("ADD_CARD", addCard);
}
