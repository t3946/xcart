import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* getProductRatingsAndReviews(action): Generator {
  const { data } = action.payload;

  const res: any = yield api.post<any>(
    route("goods:api:reviews:get-ratings-and-reviews"),
    JSON.stringify(data)
  );

  yield put({
    type: "SAVE_PRODUCT_RATINGS",
    productId: data.productId,
    ratings: res.ratings,
  });

  yield put({
    type: "ADD_PRODUCT_REVIEWS",
    productId: data.productId,
    reviews: res.reviews,
  });
}

export default function* reviewsActionWatcher(): SagaIterator {
  yield takeLatest(
    "GET_PRODUCT_RATINGS_AND_REVIEWS",
    getProductRatingsAndReviews
  );
}
