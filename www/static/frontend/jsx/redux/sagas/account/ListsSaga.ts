import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import Store from "@client/jsx/redux/stores/Store";
import { AnyAction } from "redux";
import { editNameOnList } from "@client/modules/account/utils/edit-store-funcs/lists/edit-name-on-list";
import { EditCommentDataOnProduct } from "@client/modules/account/utils/edit-store-funcs/lists/edit-comment-data-on-product";
import { route } from "@client/jsx/utils/AppData";
import axiosInstance from "@client/jsx/utils/axiosInstance";

const getUser = () => {
  return Store.getState().user;
};

function* getLists(): Generator {
  const result: any = yield axiosInstance
    .get("/axiosInstance/account/lists/get-lists")
    .then((response) => response.data);
  yield put({
    type: "SET_LISTS",
    lists: result,
  });
}

function* createList(action: AnyAction): Generator {
  const result = yield axiosInstance
    .post("/axiosInstance/account/lists/create-lists", {
      name: action.name,
      user_id: getUser().id,
    })
    .then((response) => response.data);

  yield put({
    type: "ADD_NEW_LIST",
    list: result,
  });

  yield action.callback(result);
}

function* reorderList(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(route("account:axiosInstance:reorder-list"), {
      productIds: action.listIds.map((e) => {
        return e.product_id;
      }),
      product_list_id: action.product_list_id,
    })
    .then((response) => response.data);
}

function* deleteList(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(route("account:axiosInstance:delete-list"), action.listId)
    .then((response) => response.data);

  yield action.callback();

  yield put({
    type: "SET_LISTS",
    lists: Store.getState().lists.lists.filter((e) => {
      if (e.product_list_id !== action.listId) {
        return e;
      }
    }),
  });
}

function* moveProduct(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/move-product`,
      {
        fromListId: action.fromListId,
        toListId: action.toListId.value,
        product: action.product.product_id,
      }
    )
    .then((response) => response.data);
}

function* encryptUrl(action: AnyAction): Generator {
  const result: any = yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/get-url-encrypt`,
      JSON.stringify({ privateType: action.privateType, hash: action.hash })
    )
    .then((response) => response.data);

  yield action.callback(
    `http://${window.location.hostname}/account/your-lists/invite/${result.tag}/${result.text}`
  );
}

function* acceptInvite(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/accept-invite`,
      JSON.stringify({ list_id: action.listId, role: action.role })
    )
    .then((response) => response.data);

  yield put({
    type: "GET_LISTS",
  });

  yield action.callback();
}

function* editUserRights(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/edit-user-rights`,
      JSON.stringify({
        list_id: action.listId,
        user: action.userId,
        actionType: action.actionType,
      })
    )
    .then((response) => response.data);
}

function* addProductOnList(action: AnyAction): Generator {
  const product = yield axiosInstance
    .post(
      `/axiosInstance/account/lists/add-product-on-list`,
      JSON.stringify({
        listId: action.listId,
        productId: action?.productId,
        name: action.name,
      })
    )
    .then((response) => response.data);
  yield action?.callback(product);
}

function* editIdeaName(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/edit-name-in-idea`,
      JSON.stringify({
        productId: action.productId,
        name: action.name,
      })
    )
    .then((response) => response.data);

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

function* editCommentInProduct(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/edit-comment`,
      JSON.stringify({
        productId: action.productId,
        listId: action.listId,
        data: action.data,
      })
    )
    .then((response) => response.data);

  yield put({
    type: "SET_LISTS",
    lists: EditCommentDataOnProduct(
      Store.getState().lists.lists,
      action.listId,
      action.productId,
      action.data
    ),
  });

  yield action.callback();
}

function* manageList(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/manage-list`,
      JSON.stringify({
        listId: action.listId,
        data: action.data,
      })
    )
    .then((response) => response.data);

  yield put({
    type: "SET_LISTS",
    lists: Store.getState().lists.lists.map((e) =>
      e.product_list_id === action.listId ? { ...e, ...action.data } : e
    ),
  });

  yield action.callback();
}

function* deleteProduct(action: AnyAction): Generator {
  yield axiosInstance
    .post<any>(
      `/account/axiosInstance/lists/delete-product`,
      JSON.stringify({
        listId: action.product_list_id,
        product: action.list_items_id,
      })
    )
    .then((response) => response.data);

  action?.callback();
}

function* undoDeleteProduct(action: AnyAction): Generator {
  yield axiosInstance
    .post<number>(
      `/account/axiosInstance/lists/undo-delete-product`,
      JSON.stringify({
        product: action.product,
      })
    )
    .then((response) => response.data);
}

export function* listsActionWatcher(): SagaIterator {
  yield takeLatest("GET_LISTS", getLists);
  yield takeLatest("CREATE_LIST", createList);
  yield takeLatest("REORDER_LIST", reorderList);
  yield takeLatest("DELETE_LIST", deleteList);
  yield takeLatest("MOVE_PRODUCT", moveProduct);
  yield takeLatest("ENCRYPT_URL", encryptUrl);
  yield takeLatest("ACCEPT_INVITE", acceptInvite);
  yield takeLatest("EDIT_USER_RIGHTS", editUserRights);
  yield takeLatest("ADD_PRODUCT_ON_LIST", addProductOnList);
  yield takeLatest("EDIT_IDEA_NAME", editIdeaName);
  yield takeLatest("EDIT_COMMENT_IN_PRODUCT", editCommentInProduct);
  yield takeLatest("MANAGE_LIST", manageList);
  yield takeLatest("DELETE_PRODUCT", deleteProduct);
  yield takeLatest("UNDO_DELETE_PRODUCT", undoDeleteProduct);
}
