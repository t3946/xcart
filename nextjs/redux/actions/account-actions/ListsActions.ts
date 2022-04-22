export const loadLists = (): any => ({
  type: "PRODUCT_LISTS_LOAD_LISTS",
});

export const createList = (payload: any): any => ({
  type: "PRODUCT_LISTS_CREATE",
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

//role
export const roleUpdate = (payload: any): any => ({
  type: "PRODUCT_LISTS_ROLE_UPDATE",
  payload,
});

export const roleDelete = (payload: any): any => ({
  type: "PRODUCT_LISTS_ROLE_DELETE",
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

//lists
export const updateList = (payload: any): any => ({
  type: "PRODUCT_LISTS_UPDATE_LIST",
  payload,
});

export const setLists = (lists: any): any => ({
  type: "SET_LISTS",
  lists,
});

//items
export const resetTypeAction = (): any => ({
  type: "PRODUCT_LISTS_RESET_TYPE_ACTION",
});
