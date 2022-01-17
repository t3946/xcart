import { takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import { route } from "@utils/AppData";
import axios from "axios";

const api = new ApiService();

function* getVideoHeaders(action): Generator {
  const { form, success } = action.data;

  yield api
    .post<any>(route("reviews:api:check-video-file"), JSON.stringify(form))
    .then(function (res) {
      success(res);
    });
}

function* createReview(action: Record<any, any>): Generator {
  const { data, success } = action.data;

  yield api.post<any>("/api-client/review/create", data).then(function (res) {
    success(res);
  });
}

export default function* ReviewSaga(): SagaIterator {
  yield takeLatest("CREATE_REVIEW", createReview);
  yield takeLatest("GET_VIDEO_HEADERS", getVideoHeaders);
}
