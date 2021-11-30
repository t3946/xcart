import { IndexesValues } from "@modules/account/ts/types/get-indexes-values";

export function GetListAndProductIndexes(
  items,
  listId: string,
  productId?: string
): IndexesValues {
  const editListIndex = items.findIndex(
    (e: any) => e.product_list_id === listId
  );

  const editProductIndex = items[editListIndex].products.findIndex(
    (e: any) => e.product_id === productId
  );

  return {
    editListIndex,
    editProductIndex,
  };
}
