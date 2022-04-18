import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { List } from "@modules/account/ts/types/list.type";

export const getLists = (): any => ({
  type: "GET_LISTS",
});

export const createList = (payload: any): any => ({
  type: "CREATE_LIST",
  payload,
});

export const reorderList = (listIds: string[], productListId: number): any => ({
  type: "SEND_REORDER_LIST",
  listIds,
  productListId,
});

export const transferProductList = (payload: any): any => ({
  type: "TRANSFER_PRODUCT_LIST",
  payload,
});

export const deleteList = (
  productListId: number,
  callback: () => void
): any => ({
  type: "SEND_DELETE_LIST",
  productListId,
  callback,
});

export const deleteProduct = (
  list_item_id: number,
  callback?: () => void
): any => ({
  type: "SEND_DELETE_PRODUCT",
  list_item_id,
  callback,
});

export const undoDeleteProduct = (payload: any): any => ({
  type: "UNDO_DELETE_PRODUCT",
  payload,
});

export const encryptUrl = (payload: any): any => ({
  type: "ENCRYPT_URL",
  payload,
});

export const editUserRights = (
  listId: number,
  userId: string,
  actionType: UserRightsActionsEnum,
  callback?: () => void
): any => ({
  type: "EDIT_USER_RIGHTS",
  listId,
  userId,
  actionType,
  callback,
});

export const addProduct = (
  listId: string,
  productId?: string,
  name?: string,
  callback?: (idea) => void
): any => ({
  type: "ADD_PRODUCT_ON_LIST",
  listId,
  productId,
  name,
  callback,
});

export const createIdea = (payload: any): any => ({
  type: "PRODUCT_LISTS_CREATE_IDEA",
  payload,
});

export const deleteItem = (payload: any): any => ({
  type: "PRODUCT_LISTS_DELETE_ITEM",
  payload,
});

export const editIdea = (payload: any): any => ({
  type: "PRODUCT_LISTS_EDIT_IDEA",
  payload,
});

export const editCommentProduct = (payload: any): any => ({
  type: "EDIT_COMMENT_PRODUCT",
  payload,
});

export const manageList = (payload: any): any => ({
  type: "MANAGE_LIST",
  payload,
});
export const fetchLists = () => ({
  type: "FETCH_LISTS",
});
export const setListView = (currentList: List) => ({
  type: "SET_LIST_VIEW",
  currentList,
});

export const dropByHash = (hash: List) => ({
  type: "LIST_DROP_BY_HASH",
  hash,
});

export const fetchListByCache = (cache: string) => ({
  type: "FETCH_LIST",
  cache,
});
