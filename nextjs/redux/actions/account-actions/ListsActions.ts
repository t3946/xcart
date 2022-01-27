import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { EditCommentRequestData } from "@modules/account/ts/types/edit-comment-request-data";
import { ManageListRequestData } from "@modules/account/ts/types/manage-list-form.types";
import { List } from "@modules/account/ts/types/list.type";

export const getLists = (): any => ({
  type: "GET_LISTS",
});

export const setLists = (lists): any => ({
  type: "SET_LISTS",
  lists,
});

export const createList = (
  name: string,
  callback: (hash: string) => void,
  actionType: "list" | "product" | undefined
): any => ({
  type: "CREATE_LIST",
  name,
  callback,
  actionType,
});

export const reorderList = (listIds: string[], productListId: number): any => ({
  type: "SEND_REORDER_LIST",
  listIds,
  productListId,
});

export const transferProductList = (
  fromListId: number,
  toListId: number,
  productId: number
): any => ({
  type: "TRANSFER_PRODUCT_LIST",
  fromListId,
  toListId,
  productId,
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
  productListId: number,
  productId: number,
  callback?: () => void
): any => ({
  type: "SEND_DELETE_PRODUCT",
  productListId,
  productId,
  callback,
});

export const undoDeleteProduct = (
  product_list_id: string,
  list_items_id: string,
  product: any
): any => ({
  type: "UNDO_DELETE_PRODUCT",
  product_list_id,
  list_items_id,
  product,
});

export const encryptUrl = (
  privateType: ShowSharedStatusEnum,
  hash: string,
  callback: (url: string) => void
): any => ({
  type: "ENCRYPT_URL",
  hash,
  privateType,
  callback,
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

export const editIdeaName = (
  listId: string,
  productId: string,
  name: string,
  callback: () => void
): any => ({
  type: "EDIT_IDEA_NAME",
  listId,
  productId,
  name,
  callback,
});

export const editCommentProduct = (
  productListId: number,
  productId: number,
  data: EditCommentRequestData,
  callback: () => void
): any => ({
  type: "EDIT_COMMENT_PRODUCT",
  productListId,
  productId,
  data,
  callback,
});

export const manageList = (
  productListId: number,
  data: ManageListRequestData,
  callback: () => void
): any => ({
  type: "MANAGE_LIST",
  productListId,
  data,
  callback,
});
export const fetchLists = () => ({
  type: "FETCH_LISTS",
});
export const setListView = (listView: List) => ({
  type: "SET_LIST_VIEW",
  listView,
});
export const fetchListByCache = (cache: string) => ({
  type: "FETCH_LIST",
  cache,
});
