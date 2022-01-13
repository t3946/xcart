import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";

const api = new ApiService();

function* getSuggestions(action): Generator {
  const { query, success } = action.payload;
  
  yield api.get<any>(`/search/suggestion?q=${query}`).then(function (res) {
    success(res);
  });
}

export default function* suggestionsActionWatcher(): SagaIterator {
  yield takeLatest("GET_SUGGESTIONS", getSuggestions);
}
