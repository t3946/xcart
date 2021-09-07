import { ShowSharedStatusEnum } from "@client/modules/account/ts/types/show-shared-status.enum";

export const getLists = (): any => ({
  type: "GET_LISTS",
});

export const setLists = (lists): any => ({
  type: "SET_LISTS",
  lists,
});

export const createList = (name: string, callback: () => void): any => ({
  type: "CREATE_LIST",
  name,
  callback,
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
  toListId: string,
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
  list_items_id: string
): any => ({
  type: "DELETE_PRODUCT",
  product_list_id,
  list_items_id,
});

export const undoDeleteProduct = (
  product_list_id: string,
  list_items_id: string
): any => ({
  type: "UNDO_DELETE_PRODUCT",
  product_list_id,
  list_items_id,
});

export const encryptUrl = (
  privateType: ShowSharedStatusEnum,
  callback: (url: string) => void
): any => ({
  type: "ENCRYPT_URL",
  privateType,
  callback,
});
