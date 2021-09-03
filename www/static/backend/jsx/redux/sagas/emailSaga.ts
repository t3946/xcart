import { put, takeLatest, all } from "redux-saga/effects";
import { ApiService } from "../../modules/shared/services/api.service";
import { emailStore } from "@redux/stores";
import { editCheckedInEmailItems } from "@s3stores-mail/utils";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";

const api = new ApiService();

function* getPage(action: AnyAction): Generator {
  const json: any = yield api
    .post<any>(
      `/admin/forms/api/email-list/${action.page}`,
      JSON.stringify({
        searchParams: action.searchParams,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_PAGE",
    json: editCheckedInEmailItems(
      json.objects,
      emailStore.getState().checkedItems
    ),
    itemsCount: json.meta.total,
    user: json.userInfo,
    labelList: json.labelList,
  });
}

function* getEmailInfo(action: AnyAction): Generator {
  const info: any = yield api
    .get<any>(`/admin/forms/api/email-info/${action.id}`)
    .then((response) => response);

  yield put({
    type: "SET_EMAIL_INFO",
    emailInfo: info,
  });
}

function* removeEmailLabel(action: AnyAction): Generator {
  const info: any = yield api
    .post<any>(
      `/admin/forms/api/mail/remove-label`,
      JSON.stringify({
        messageId: action.messageId,
        labelId: action.labelId,
      })
    )
    .then((response) => response);
}

function* createMailLabel(action: AnyAction): Generator {
  const labelInfo: any = yield api
    .post<any>(
      `/admin/forms/api/mail/create-label`,
      JSON.stringify({
        messageId: action.messageId,
        name: action.nameLabel,
        color: action.color,
      })
    )
    .then((response) => response);
  yield put({
    type: "CREATE_MAIL_LABEL",
    parentMessageId: action.parentMessageId,
    messageId: action.messageId,
    labelInfo: labelInfo,
  });
}

function* addLabelEMail(action: AnyAction): Generator {
  const info: any = yield api
    .post<any>(
      `/admin/forms/api/add-label-email`,
      JSON.stringify({
        messageId: action.messageId,
        labelId: action.labelId,
      })
    )
    .then((response) => response);
}

function* getTemplates(): Generator {
  const templates: any = yield api
    .get<any>(`/admin/forms/api/get-templates`)
    .then((response) => response);

  yield put({
    type: "SET_TEMPLATES",
    templates: templates,
  });
}

function* editFavorite(action: AnyAction): Generator {
  if (action.error) {
    return;
  }
  try {
    yield api.post(
      `/admin/forms/api/edit-favorite`,
      JSON.stringify({
        itemsId: action.favoriteItems,
        value: action.value,
      })
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
      JSON.stringify(action.actionItems)
    );
  } catch (error) {
    yield put({
      type: "EDIT_ACTIONS",
      error: true,
      actionItems: action.actionItems,
    });
  }
}

function* setViewed(action: AnyAction): Generator {
  if (action.error) {
    return;
  }
  try {
    yield api.post(
      `/admin/forms/api/set-viewed`,
      JSON.stringify({
        emailId: action.emailId,
        value: action.value,
      })
    );
  } catch (error) {
    yield put({
      type: "SET_VIEWED",
      error: true,
      emailId: action.emailId,
    });
  }
}

function* sendEmail(action: AnyAction): Generator {
  try {
    const formData = new FormData();
    console.log("Отправка письма", action);
    Object.entries(action.email).forEach(([key, value]: any) => {
      if (Array.isArray(value)) {
        value.forEach((e) => {
          formData.append(`${key}[]`, e);
        });
        return;
      }
      if (key === "date" && value) {
        value = value.getTime() / 1000;
      }
      formData.append(key, value);
    });

    yield api.post(`/admin/forms/api/send-email`, formData);
  } catch (e) {}
}
function* getChildList(action: AnyAction): Generator {
  if (action.error) {
    return;
  }
  const thread: any = yield api
    .get<any>(`/admin/forms/api/email/children/${action.id}`)
    .then((response) => response);

  yield put({
    type: "SET_EMAIL_CHILDREN",
    messageId: action.id,
    thread,
  });
}
function* editFavoriteEmail(action: AnyAction): Generator {
  const data = yield api.post(
    `/admin/forms/api/edit-favorite`,
    JSON.stringify({
      itemsId: [action.messageId],
      value: action.value,
    })
  );
}

function* actionWatcher(): SagaIterator {
  yield takeLatest("GET_PAGE", getPage);
  yield takeLatest("EDIT_FAVORITES", editFavorite);
  yield takeLatest("EDIT_ACTIONS", editAction);
  yield takeLatest("SET_VIEWED", setViewed);
  yield takeLatest("GET_TEMPLATES", getTemplates);
  yield takeLatest("SEND_EMAIL", sendEmail);
  yield takeLatest("GET_EMAIL_INFO", getEmailInfo);
  yield takeLatest("CREATE_LABEL", createMailLabel);
  yield takeLatest("REMOVE_LABEL", removeEmailLabel);
  yield takeLatest("ADD_LABEL_MAIL", addLabelEMail);
  yield takeLatest("GET_CHILD_LIST", getChildList);
  yield takeLatest("EDIT_FAVORITE_EMAIL", editFavoriteEmail);
}

export default function* rootSaga(): Generator {
  yield all([actionWatcher()]);
}
