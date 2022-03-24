// @ts-ignore
import { GetListAndProductIndexes } from "@client/modules/account/utils/edit-store-funcs/lists/get-product";

export function editNameOnList(
  lists: Array<any>,
  listId: string,
  product_id: string,
  newValue: string
): Array<any> {
  const mass = lists;

  const indexes = GetListAndProductIndexes(
    mass,
    listId,
    product_id
  );

  mass[indexes.editListIndex].products[indexes.editProductIndex].product.name =
    newValue;

  return mass;
}
