import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import Store from "@redux/stores/Store";
import { AnyAction } from "redux";
import axios from "axios";
import { List } from "@modules/account/ts/types/list.type";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";

const api = new ApiService();

function* getLists(): Generator {
  let lists;

  yield axios
    .get("/api-client/user/lists/get-all")
    .then((res) => (lists = res.data));

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
  yield axios.post("/api-client/user/lists/reorder-product", {
    productIds: action.listIds.map((e) => e.list_item_id),
  });
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
        list_item_id: productId,
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
  const { data, success } = action.payload;

  axios.post<any>(`/api/account/lists/get-url-encrypt`, data).then(success);
}

function* editUserRights(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/api/account/lists/edit-user-rights`,
      JSON.stringify({
        list_id: action.listId,
        user_id: action.userId,
        action: action.actionType,
      })
    )
    .then((response) => response);

  window.location.reload();
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

function* editCommentProduct(action: AnyAction): Generator {
  const { data, success } = action.payload;

  yield put({
    type: "EDIT_COMMENT_LIST_VIEW",
    data,
  });

  success && (yield success());

  yield axios.post("/api-client/user/lists/item/edit", data);
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
  const { list_item_id } = action;
  yield api
    .post<any>(
      `/api/account/lists/delete-product`,
      JSON.stringify({
        list_item_id,
      })
    )
    .then((response) => response);
  yield put({
    type: "DELETE_PRODUCT_LIST_VIEW",
    list_item_id,
  });

  action?.callback && action?.callback();
}

function* undoDeleteProduct(action: AnyAction): Generator {
  const { data } = action.payload;

  yield axios.post(`/api-client/user/lists/item/restore`, data);
}

function* fetchListView(action: AnyAction): Generator {
  const listView = yield api
    .get(`/api/account/lists/get/${action.cache}`)
    .then((res: List | null) => res);

  // list removed
  if (listView === null) {
    yield put({
      type: "LIST_DROP_BY_HASH",
      hash: action.cache,
    });

    yield put({
      type: "SET_LIST_VIEW",
      listView: null,
    });

    return;
  }

  listView.listType = listView.users.length
    ? ListPrivateEnum.SHARED
    : ListPrivateEnum.PRIVATE;

  yield put({
    type: "SET_LIST_VIEW",
    listView,
  });
}

function* editIdea(action: AnyAction): Generator {
  const { data } = action.payload;

  yield put({
    type: "EDIT_IDEA",
    name: data.name,
    list_idea_id: data.list_idea_id,
  });

  yield axios.post(`/api-client/user/lists/idea/edit`, data);
}

function* createIdea(action: AnyAction): Generator {
  const { data, success } = action.payload;
  let newListItem;

  yield axios
    .post(`/api-client/user/lists/idea/create`, {
      product_list_id: data.product_list_id,
      name: data.name,
    })
    .then((res) => {
      newListItem = res.data.list_item;
    });

  yield put({
    type: "ADD_PRODUCT_TO_LIST",
    listItem: newListItem,
    productListId: data.product_list_id,
  });

  yield success();
}

function* deleteItem(action: AnyAction): Generator {
  const { list_item_id } = action.payload.data;

  yield axios.post("/api-client/user/lists/item/delete", {
    list_item_id,
  });

  yield put({
    type: "DELETE_PRODUCT_LIST_VIEW",
    list_item_id,
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
  yield takeLatest("EDIT_COMMENT_PRODUCT", editCommentProduct);
  yield takeLatest("MANAGE_LIST", manageList);
  yield takeLatest("SEND_DELETE_PRODUCT", deleteProduct);
  yield takeLatest("UNDO_DELETE_PRODUCT", undoDeleteProduct);
  yield takeLatest("FETCH_LIST", fetchListView);

  //idea
  yield takeLatest("PRODUCT_LISTS_EDIT_IDEA", editIdea);
  yield takeLatest("PRODUCT_LISTS_CREATE_IDEA", createIdea);

  //item
  yield takeLatest("PRODUCT_LISTS_DELETE_ITEM", deleteItem);
}
