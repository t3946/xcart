import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* getProductRatings(action): Generator {
  const { data } = action.payload;

  const ratings = yield api.post<any>(
    route("account:api:get-product-ratings"),
    JSON.stringify(data)
  );

  yield put({
    type: "SAVE_PRODUCT_RATINGS",
    productId: data.productId,
    ratings,
  });
}

export default function* ratingsActionWatcher(): SagaIterator {
  yield takeLatest("GET_PRODUCT_RATINGS", getProductRatings);
}
