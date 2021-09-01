export const getLists = (): any => ({
  type: "GET_LISTS",
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
