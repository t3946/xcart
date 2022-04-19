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
  const { data, callback } = action.payload;
  let newList;

  yield axios.post("/api-client/user/lists/create", data).then((res) => {
    newList = res.data;
  });

  yield put({
    type: "ADD_LIST",
    list: newList,
  });

  callback(newList);
}

function* reorderList(action: AnyAction): Generator {
  yield axios.post("/api-client/user/lists/reorder-product", {
    productIds: action.listIds.map((e) => e.list_item_id),
  });
}

function* deleteList(action: AnyAction): Generator {
  const { data } = action.payload;

  yield axios.post("/api-client/user/lists/delete", data);
}

function* transferProductList(action: AnyAction): Generator {
  const { data } = action.payload;

  yield put({
    type: "SET_TRANSFER_PRODUCT",
    product_list_id: data.product_list_id,
    list_item_id: data.list_item_id,
  });

  yield axios.post(`/api-client/user/lists/transfer`, data);
}

function* inviteGenerate(action: AnyAction): Generator {
  const { data, success } = action.payload;

  yield axios
    .post("/api-client/user/lists/invite/generate", data)
    .then(success);
}

function* inviteUse(action: AnyAction): Generator {
  const { data, callback } = action.payload;
  const { iv, content } = data;

  yield axios.get(`/api-client/user/lists/invite/use/${iv}/${content}`);

  yield getLists();

  callback();
}

function* changeUserRole(action: AnyAction): Generator {
  const { data } = action.payload;

  yield axios.post("/api-client/user/lists/change-user-role", data);

  window.location.reload();
}

function* editCommentProduct(action: AnyAction): Generator {
  const { data, callback } = action.payload;

  yield put({
    type: "EDIT_COMMENT_LIST_VIEW",
    data,
  });

  callback && (yield callback());

  yield axios.post("/api-client/user/lists/item/edit", data);
}

function* manageList(action: AnyAction): Generator {
  const { data } = action.payload;

  yield axios
    .post("/api-client/user/lists/update", data)
    .then((response) => response);

  yield put({
    type: "MANAGE_LIST_VIEW",
    product_list_id: data.product_list_id,
    data,
  });
}

function* undoDeleteProduct(action: AnyAction): Generator {
  const { data } = action.payload;

  yield axios.post(`/api-client/user/lists/item/restore`, data);
}

function* editIdea(action: AnyAction): Generator {
  const { data } = action.payload;

  yield put({
    type: "EDIT_IDEA",
    data: {
      name: data.name,
      list_idea_id: data.list_idea_id,
    },
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
  yield takeLatest("TRANSFER_PRODUCT_LIST", transferProductList);
  yield takeLatest("EDIT_COMMENT_PRODUCT", editCommentProduct);
  yield takeLatest("MANAGE_LIST", manageList);
  yield takeLatest("UNDO_DELETE_PRODUCT", undoDeleteProduct);

  //idea
  yield takeLatest("PRODUCT_LISTS_EDIT_IDEA", editIdea);
  yield takeLatest("PRODUCT_LISTS_CREATE_IDEA", createIdea);

  //item
  yield takeLatest("PRODUCT_LISTS_DELETE_ITEM", deleteItem);

  //invite
  yield takeLatest("PRODUCT_LISTS_INVITE_GENERATE", inviteGenerate);
  yield takeLatest("PRODUCT_LISTS_INVITE_USE", inviteUse);

  //list
  yield takeLatest("PRODUCT_LISTS_DELETE_LIST", deleteList);
  yield takeLatest("PRODUCT_LISTS_CHANGE_USER_ROLE", changeUserRole);
}
