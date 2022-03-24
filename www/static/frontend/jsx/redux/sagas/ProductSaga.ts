import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";

const api = new ApiService();

function* getProductRatingsAndReviews(action): Generator {
  const { data } = action.payload;

  const res: any = yield api.post<any>(
   "/reviews/api/get-ratings-and-reviews",
    JSON.stringify(data)
  );

  yield put({
    type: "SAVE_PRODUCT_RATINGS",
    productId: data.productId,
    ratings: res.ratings,
  });

  yield put({
    type: "SET_PRODUCT_REVIEWS_ORDERS",
    reviews: res.reviews,
  });

  yield put({
    type: "SET_PRODUCT",
    product: res.product,
  });

  yield put({
    type: "SET_IS_USER_CAN_WRITE_REVIEW",
    isUserCanWriteReview: res.canWriteReview,
  });
}

function* markHelpful(action): Generator {
  const { data, success } = action.payload;

  yield api
    .post<any>("/reviews/api/mark-helpful", JSON.stringify(data))
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
    .post<any>("/reviews/api/unmark-helpful", JSON.stringify(data))
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
    .post<any>("/reviews/api/get-reviews", JSON.stringify(data))
    .then(function (res) {
      success(res);
    });
}

function* reportReview(action): Generator {
  const { data, success, error } = action.payload;

  yield api
    .post<any>("/api-client/review/report-abuse", JSON.stringify(data))
    .then(function (res) {
      success(res);
    })
    .catch(error);
}

export default function* reviewsActionWatcher(): SagaIterator {
  yield takeLatest(
    "GET_PRODUCT_RATINGS_AND_REVIEWS",
    getProductRatingsAndReviews
  );

  yield takeLatest("MARK_HELPFUL", markHelpful);
  yield takeLatest("UNMARK_HELPFUL", unmarkHelpful);
  yield takeLatest("GET_REVIEWS", getReviews);
  yield takeLatest("REPORT_REVIEW", reportReview);
}
