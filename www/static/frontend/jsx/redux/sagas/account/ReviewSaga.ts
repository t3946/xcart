import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* getVideoHeaders(action): Generator {
  const { form, success } = action.data;

  yield api
    .post<any>(route("reviews:api:check-video-file"), JSON.stringify(form))
    .then(function (res) {
      success(res);
    });
}

function* createReview(action): Generator {
  const { form, success } = action.data;

  yield api
    .post<any>(route("reviews:api:create"), form, {})
    .then(function (res) {
      success(res);
    });
}

export default function* ReviewSaga(): SagaIterator {
  yield takeLatest("CREATE_REVIEW", createReview);
  yield takeLatest("GET_VIDEO_HEADERS", getVideoHeaders);
}
