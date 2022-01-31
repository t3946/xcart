import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import Store from "@redux/stores/Store";
import { AnyAction } from "redux";
import { editNameOnList } from "@modules/account/utils/edit-store-funcs/lists/edit-name-on-list";
import { route } from "@utils/AppData";
import axios from "axios";
import { List } from "@modules/account/ts/types/list.type";

const api = new ApiService();

const getUser = () => {
  return Store.getState().user;
};

function* getLists(): Generator {
  const lists = yield api
    .get("/api/account/lists/get-lists")
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists,
  });
}

function* createList(action: AnyAction): Generator {
  const data: List = yield api
    .post(
      "/api/account/lists/create-lists",
      JSON.stringify({
        name: action.name,
      })
    )
    .then((response) => response);

  yield put({
    type: "ADD_LIST",
    data,
  });

  yield action.callback(data.cacheUrl);
}

function* reorderList(action: AnyAction): Generator {
  yield api
    .post(
      "/api/account/lists/reorder-products",
      JSON.stringify({
        productIds: action.listIds.map((e) => e.list_items_id),
        productListId: action.productListId,
      })
    )
    .then((response) => response);
}

function* deleteList(action: AnyAction): Generator {
  const { productListId } = action;
  yield axios
    .delete(`/api/account/lists/delete-list/${productListId}`)
    .then((res) => res);
  yield put({
    type: "DELETE_LIST",
    productListId,
  });
  yield action.callback();
}

function* transferProductList(action: AnyAction): Generator {
  const { fromListId, toListId, productId } = action;
  yield api
    .post<any>(
      `/api/account/lists/transfer-product`,
      JSON.stringify({
        fromListId: fromListId,
        toListId: toListId,
        productId: productId,
      })
    )
    .then((response) => response);
  yield put({
    type: "SET_TRANSFER_PRODUCT",
    fromListId,
    productId,
    toListId,
  });
}

function* encryptUrl(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      `/api/account/lists/get-url-encrypt`,
      JSON.stringify({ privateType: action.privateType, hash: action.hash })
    )
    .then((response) => response);
  const url = `http://${window.location.hostname}/account/shopping-lists/invite/${result.tag}/${result.text}`;
  yield action.callback(url);
}

function* editUserRights(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/api/lists/edit-user-rights`,
      JSON.stringify({
        list_id: action.listId,
        user: action.userId,
        actionType: action.actionType,
      })
    )
    .then((response) => response);
}

function* addProductOnList(action: AnyAction): Generator {
  const product = yield api
    .post(
      `/api/account/lists/add-product-on-list`,
      JSON.stringify({
        listId: action.listId,
        productId: action?.productId,
        name: action.name,
      })
    )
    .then((response) => response);

  yield put({
    type: "ADD_PRODUCT_TO_LIST",
    product,
    productListId: action.listId,
  });

  yield action?.callback(product);
}

function* editIdeaName(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/account/api/lists/edit-name-in-idea`,
      JSON.stringify({
        productId: action.productId,
        name: action.name,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: editNameOnList(
      Store.getState().lists.lists,
      action.listId,
      action.productId,
      action.name
    ),
  });

  yield action.callback();
}

function* editCommentProduct(action: AnyAction): Generator {
  const { productId, productListId, data } = action;
  yield api
    .post(
      `/api/account/lists/edit-comment`,
      JSON.stringify({
        productId,
        productListId,
        data,
      })
    )
    .then((res) => res);
  yield put({ type: "EDIT_COMMENT_LIST_VIEW", productId, data });
  yield action.callback();
}

function* manageList(action: AnyAction): Generator {
  const { productListId, data } = action;
  yield api
    .post(
      `/api/account/lists/manage-list`,
      JSON.stringify({
        productListId,
        data,
      })
    )
    .then((response) => response);
  yield put({
    type: "MANAGE_LIST_VIEW",
    productListId,
    data,
  });

  yield action.callback();
}

function* deleteProduct(action: AnyAction): Generator {
  const { list_items_id } = action;
  yield api
    .post<any>(
      `/api/account/lists/delete-product`,
      JSON.stringify({
        list_items_id,
      })
    )
    .then((response) => response);
  yield put({
    type: "DELETE_PRODUCT_LIST_VIEW",
    list_items_id,
  });

  action?.callback && action?.callback();
}

function* undoDeleteProduct(action: AnyAction): Generator {
  yield api
    .post<number>(
      `/api/account/lists/undo-delete-product`,
      JSON.stringify({
        product: action.product,
      })
    )
    .then((response) => response);
}
function* fetchListView(action: AnyAction): Generator {
  const listView = yield api
    .get(`/api/account/lists/get/${action.cache}`)
    .then((res) => res);
  yield put({
    type: "SET_LIST_VIEW",
    listView,
  });
}

export function* listsActionWatcher(): SagaIterator {
  yield takeLatest("FETCH_LISTS", getLists);
  yield takeLatest("CREATE_LIST", createList);
  yield takeLatest("SEND_REORDER_LIST", reorderList);
  yield takeLatest("SEND_DELETE_LIST", deleteList);
  yield takeLatest("TRANSFER_PRODUCT_LIST", transferProductList);
  yield takeLatest("ENCRYPT_URL", encryptUrl);
  yield takeLatest("EDIT_USER_RIGHTS", editUserRights);
  yield takeLatest("ADD_PRODUCT_ON_LIST", addProductOnList);
  yield takeLatest("EDIT_IDEA_NAME", editIdeaName);
  yield takeLatest("EDIT_COMMENT_PRODUCT", editCommentProduct);
  yield takeLatest("MANAGE_LIST", manageList);
  yield takeLatest("SEND_DELETE_PRODUCT", deleteProduct);
  yield takeLatest("UNDO_DELETE_PRODUCT", undoDeleteProduct);
  yield takeLatest("FETCH_LIST", fetchListView);
}
