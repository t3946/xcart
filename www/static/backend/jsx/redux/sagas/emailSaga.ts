import { put, takeLatest, all } from "redux-saga/effects";
import { ApiService } from "../../modules/shared/services/api.service";
import { emailStore } from "../stores/emailStore";
const api = new ApiService();

function* getPage(action) {
  const options = emailStore.getState()?.searchOptions;

  const json = yield api
    .get<any>(
      `http://localhost:3000/menu-items?${
        options.title ? "title=" + options.title + "&" : ""
      }_page=${action.page}&_limit=3`
    )
    .then((response) => {
      if (Array.isArray(response)) return response;
      return [response];
    });

  yield put({ type: "SET_PAGE", json: json, page: action.page, loading: true });
}

function* getItemsCount() {
  const count = yield api
    .get<any>(`http://localhost:3000/menu-items`)
    .then((response) => {
      return response.length;
    });

  yield put({ type: "SET_ITEMS_COUNT", itemsCount: count, loading: true });
}

function* actionWatcher() {
  yield takeLatest("GET_PAGE", getPage);
  yield takeLatest("GET_ITEMS_COUNT", getItemsCount);
}

export default function* rootSaga(): any {
  yield all([actionWatcher()]);
}
