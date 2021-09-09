import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@client/modules/shared/services/api.service";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { AnyAction } from "redux";

const api = new ApiService();

const getUser = () => {
  return accountStore.getState().user;
};

function* getLists(action: AnyAction): Generator {
  console.log(getUser());
  const result: any = yield api
    .post<any>(`/account/api/lists/get-lists`, getUser().id)
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: result,
  });
}

function* createList(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/account/api/lists/create-lists`,
      JSON.stringify({
        name: action.name,
        user_id: getUser().id,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: accountStore.getState().lists.lists.concat(result),
  });

  yield action.callback();
}

function* reorderList(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/account/api/lists/reorder-products`,
      JSON.stringify(
        action.listIds.map((e) => {
          return e.product_id;
        })
      )
    )
    .then((response) => response);
}

function* deleteList(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(`/account/api/lists/delete-list`, action.listId)
    .then((response) => response);

  yield action.callback();

  yield put({
    type: "SET_LISTS",
    lists: accountStore.getState().lists.lists.filter((e) => {
      if (e.product_list_id !== action.listId) {
        return e;
      }
    }),
  });
}

function* moveProduct(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/account/api/lists/move-product`,
      JSON.stringify({
        fromListId: action.fromListId,
        toListId: action.toListId.value,
        product: action.product.product_id,
      })
    )
    .then((response) => response);
}

function* encryptUrl(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(`/account/api/lists/get-url-encrypt`, action.privateType)
    .then((response) => response);

  yield action.callback(
    `http://localhost/account/your-lists/invite/${result.text}`
  );
}

export function* listsActionWatcher(): SagaIterator {
  yield takeLatest("GET_LISTS", getLists);
  yield takeLatest("CREATE_LIST", createList);
  yield takeLatest("REORDER_LIST", reorderList);
  yield takeLatest("DELETE_LIST", deleteList);
  yield takeLatest("MOVE_PRODUCT", moveProduct);
  yield takeLatest("ENCRYPT_URL", encryptUrl);
}
