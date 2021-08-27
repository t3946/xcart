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
  startIndex: number,
  endIndex: number,
  product_list_id: number
): any => ({
  type: "REORDER_LIST",
  listIds,
  startIndex,
  endIndex,
  product_list_id,
});
