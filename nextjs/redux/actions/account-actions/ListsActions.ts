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

export const deleteList = (payload: any): any => ({
  type: "PRODUCT_LISTS_DELETE_LIST",
  payload,
});

export const undoDeleteProduct = (payload: any): any => ({
  type: "UNDO_DELETE_PRODUCT",
  payload,
});

export const getInvite = (payload: any): any => ({
  type: "PRODUCT_LISTS_INVITE_GENERATE",
  payload,
});

export const inviteUse = (payload: any): any => ({
  type: "PRODUCT_LISTS_INVITE_USE",
  payload,
});

export const changeUserRole = (payload: any): any => ({
  type: "PRODUCT_LISTS_CHANGE_USER_ROLE",
  payload,
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

