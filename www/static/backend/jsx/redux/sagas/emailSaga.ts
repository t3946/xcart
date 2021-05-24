import { put, takeLatest, all } from "redux-saga/effects";
import { ApiService } from "../../modules/shared/services/api.service";
import { emailStore } from "@redux/stores";
import {
  convertActionDataToPost,
  editCheckedInEmailItems,
} from "@s3stores-mail/utils";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
const api = new ApiService();

function* getPage(action: AnyAction): Generator {
  const json: any = yield api
    .get<any>(`/admin/forms/api/email-list/${action.page}`)
    .then((response) => response);

  yield put({
    type: "SET_PAGE",
    json: editCheckedInEmailItems(
      json.objects,
      emailStore.getState().checkedItems
    ),
    itemsCount: json.meta.total,
  });
}

function* editFavorite(action: AnyAction): Generator {
  if (action.error) {
    return;
  }
  try {
    yield api.post(
      `/admin/forms/api/edit-favorite`,
      JSON.stringify(action.favoriteItems)
    );
  } catch (error) {
    yield put({
      type: "EDIT_FAVORITES",
      error: true,
      favoriteItems: action.favoriteItems,
    });
  }
}

function* editAction(action: AnyAction): Generator {
  if (action.error) {
    return;
  }
  try {
    yield api.post(
      `/admin/forms/api/edit-action`,
      JSON.stringify(convertActionDataToPost(action.actionItems))
    );
  } catch (error) {
    yield put({
      type: "EDIT_ACTIONS",
      error: true,
      actionItems: action.actionItems,
    });
  }
}

function* actionWatcher(): SagaIterator {
  yield takeLatest("GET_PAGE", getPage);
  yield takeLatest("EDIT_FAVORITES", editFavorite);
  yield takeLatest("EDIT_ACTIONS", editAction);
}

export default function* rootSaga(): Generator {
  yield all([actionWatcher()]);
}
