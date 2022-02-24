import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { route } from "@utils/AppData";

const api = new ApiService();

function* getProductRatingsAndReviews(action): Generator {
  const { data } = action.payload;

  const res: any = yield api.post<any>(
    route("reviews:api:get-ratings-and-reviews"),
    JSON.stringify(data)
  );

  yield put({
    type: "SAVE_PRODUCT_RATINGS",
    productId: data.productId,
    ratings: res.ratings,
  });

  yield put({
    type: "SET_PRODUCT_REVIEWS_ORDERS",
    reviews: res.reviewsOrders,
  });

  yield put({
    type: "SET_PRODUCT",
    product: res.product,
  });
}

function* markHelpful(action): Generator {
  const { data, success } = action.payload;

  yield api
    .post<any>(route("reviews:api:mark-helpful"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });

  yield put({
    type: "SET_HELPFUL",
    reviewId: data.reviewId,
    helpful: true,
  });
}

function* unmarkHelpful(action): Generator {
  const { data, success } = action.payload;

  yield api
    .post<any>(route("reviews:api:unmark-helpful"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });

  yield put({
    type: "SET_HELPFUL",
    reviewId: data.reviewId,
    helpful: false,
  });
}

function* getReviews(action): Generator {
  const { data, success } = action.payload;

  yield api
    .post<any>(route("reviews:api:get-reviews"), JSON.stringify(data))
    .then(function (res) {
      success(res);
    });
}

export default function* reviewsActionWatcher(): SagaIterator {
  yield takeLatest(
    "GET_PRODUCT_RATINGS_AND_REVIEWS",
    getProductRatingsAndReviews
  );

  yield takeLatest("MARK_HELPFUL", markHelpful);
  yield takeLatest("UNMARK_HELPFUL", unmarkHelpful);
  yield takeLatest("GET_REVIEWS", getReviews);
}
