import { takeLatest } from "redux-saga/effects";
import { ApiService } from "@client/modules/shared/services/api.service";
import { AnyAction } from "redux";
import { SagaIterator } from "redux-saga";
import { route } from "@client/jsx/utils/AppData";

const api = new ApiService();

function* editName(action: AnyAction) {
  const { form, success, error, complete } = action.payload;

  const data = JSON.stringify({
    EditNameForm: form,
  });

  console.log(form, data);

  yield api.post<any>(route("account:api:edit-name"), data).then((res) => {
    res.errors ? error(res.errors) : success(res);

    complete();

    return res;
  });
}

function* loginAndSecuritySaga(): SagaIterator {
  yield takeLatest("ACCOUNT_EDIT_NAME", editName);
}

export default loginAndSecuritySaga;
