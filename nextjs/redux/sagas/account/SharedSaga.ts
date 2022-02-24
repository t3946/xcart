import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";

const api = new ApiService();

function* getTerritory(): Generator {
  const result: any = yield api
    .get<any>(`/api/account/get-territory`)
    .then((response) => response);

  yield put({
    type: "SET_TERRITORY",
    countries: result.countries,
    states: result.states,
  });
}

export function* sharedActionWatcher(): SagaIterator {
  yield takeLatest("GET_TERRITORY", getTerritory);
}
