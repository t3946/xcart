import { ShowSharedStatusEnum } from "@modules/account/ts/types/show-shared-status.enum";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { UserRightsActionsEnum } from "@modules/account/ts/consts/user-rights-actions.enum";
import { EditCommentRequestData } from "@modules/account/ts/types/edit-comment-request-data";
import { ManageListRequestData } from "@modules/account/ts/types/manage-list-form.types";

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

export const reorderList = (
  listIds: string[],
  product_list_id: number
): any => ({
  type: "REORDER_LIST",
  listIds,
  product_list_id,
});

export const moveProduct = (
  fromListId: string,
  toListId: { value: string },
  product: any
): any => ({
  type: "MOVE_PRODUCT",
  fromListId,
  toListId,
  product,
});

export const deleteList = (listId: string, callback: () => void): any => ({
  type: "DELETE_LIST",
  listId,
  callback,
});

export const deleteProduct = (
  product_list_id: string,
  list_items_id: string,
  callback?: () => void
): any => ({
  type: "DELETE_PRODUCT",
  product_list_id,
  list_items_id,
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

export const acceptInvite = (
  listId: ShowSharedStatusEnum,
  role: UserPrivateVariantsEnum,
  callback: () => void
): any => ({
  type: "ACCEPT_INVITE",
  listId,
  role,
  callback,
});

export const editUserRights = (
  listId: string,
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

export const editCommentInProduct = (
  listId: string,
  productId: string,
  data: EditCommentRequestData,
  callback: () => void
): any => ({
  type: "EDIT_COMMENT_IN_PRODUCT",
  listId,
  productId,
  data,
  callback,
});

export const manageList = (
  listId: string,
  data: ManageListRequestData,
  callback: () => void
): any => ({
  type: "MANAGE_LIST",
  listId,
  data,
  callback,
});
