import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import axios from "axios";

function* load(action: Record<any, any>): Generator {
  const { callback, url, page } = action.payload;

  const result: any = yield axios
    .get(`/api${url}?page=${page}`, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
    .then((res) => res.data);

  const { items, pager } = result;

  console.log("loaded data", {items, pager});

  yield put({
    type: "PRODUCT_SLIDER_FEATURED_ADD_ITEMS",
    items,
  });

  yield put({
    type: "PRODUCT_SLIDER_FEATURED_SET_PAGINATION",
    pagination: {
      current: pager.currentPage,
      total: pager.pagesCount,
    },
  });

  callback();
}

export default function* reviewsActionWatcher(): SagaIterator {
  yield takeLatest("PRODUCT_SLIDER_LOAD", load);
}
