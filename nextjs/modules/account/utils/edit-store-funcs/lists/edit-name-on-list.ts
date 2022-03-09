import { GetListAndProductIndexes } from "@modules/account/utils/edit-store-funcs/lists/get-product";
import { IndexesValues } from "@modules/account/ts/types/get-indexes-values";

export function editNameOnList(
  lists: Array<any>,
  listId: string,
  product_id: string,
  newValue: string
): Array<any> {
  const mass = lists;

  const indexes: IndexesValues = GetListAndProductIndexes(
    mass,
    listId,
    product_id
  );

  mass[indexes.editListIndex].products[indexes.editProductIndex].product.name =
    newValue;

  return mass;
}
