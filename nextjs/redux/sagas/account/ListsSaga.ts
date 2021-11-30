import { put, takeLatest } from "redux-saga/effects";
import { SagaIterator } from "redux-saga";
import { ApiService } from "@modules/shared/services/api.service";
import Store from "@redux/stores/Store";
import { AnyAction } from "redux";
import { editNameOnList } from "@modules/account/utils/edit-store-funcs/lists/edit-name-on-list";
import { EditCommentDataOnProduct } from "@modules/account/utils/edit-store-funcs/lists/edit-comment-data-on-product";
import { route } from "@utils/AppData";

const api = new ApiService();

const getUser = () => {
  return Store.getState().user;
};

function* getLists(): Generator {
  const result: any = yield api
    .get<any>(route("account:api:get-lists"))
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: result,
  });
}

function* createList(action: AnyAction): Generator {
  const result: any = yield api
    .post<any>(
      route("account:api:create-lists"),
      JSON.stringify({
        name: action.name,
        user_id: getUser().id,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: Store.getState().lists.lists.concat(result),
  });

  yield action.callback(result);
}

function* reorderList(action: AnyAction): Generator {

  yield api
    .post<any>(
      route("account:api:reorder-list"),
      JSON.stringify({
        productIds: action.listIds.map((e) => {
          return e.product_id;
        }),
        product_list_id: action.product_list_id,
      })
    )
    .then((response) => response);
}

function* deleteList(action: AnyAction): Generator {
  yield api
    .post<any>(route("account:api:delete-list"), action.listId)
    .then((response) => response);

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
  yield api
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
    .post<any>(
      `/account/api/lists/get-url-encrypt`,
      JSON.stringify({ privateType: action.privateType, hash: action.hash })
    )
    .then((response) => response);

  yield action.callback(
    `http://${window.location.hostname}/account/your-lists/invite/${result.tag}/${result.text}`
  );
}

function* acceptInvite(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/account/api/lists/accept-invite`,
      JSON.stringify({ list_id: action.listId, role: action.role })
    )
    .then((response) => response);

  yield put({
    type: "GET_LISTS",
  });

  yield action.callback();
}

function* editUserRights(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/account/api/lists/edit-user-rights`,
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
    .post<any>(
      `/account/api/lists/add-product-on-list`,
      JSON.stringify({
        listId: action.listId,
        productId: action?.productId,
        name: action.name,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: Store.getState().lists.lists.map((e) => {
      if (e.product_list_id === action.listId) {
        return {
          ...e,
          products: e.products.concat(product),
        };
      }
      return e;
    }),
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

function* editCommentInProduct(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/account/api/lists/edit-comment`,
      JSON.stringify({
        productId: action.productId,
        listId: action.listId,
        data: action.data,
      })
    )
    .then((response) => response);

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
  yield api
    .post<any>(
      `/account/api/lists/manage-list`,
      JSON.stringify({
        listId: action.listId,
        data: action.data,
      })
    )
    .then((response) => response);

  yield put({
    type: "SET_LISTS",
    lists: Store.getState().lists.lists.map((e) =>
      e.product_list_id === action.listId ? { ...e, ...action.data } : e
    ),
  });

  yield action.callback();
}

function* deleteProduct(action: AnyAction): Generator {
  yield api
    .post<any>(
      `/account/api/lists/delete-product`,
      JSON.stringify({
        listId: action.product_list_id,
        product: action.list_items_id,
      })
    )
    .then((response) => response);

  action?.callback();
}

function* undoDeleteProduct(action: AnyAction): Generator {
  yield api
    .post<number>(
      `/account/api/lists/undo-delete-product`,
      JSON.stringify({
        product: action.product,
      })
    )
    .then((response) => response);
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
