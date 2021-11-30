import { IndexesValues } from "@modules/account/ts/types/get-indexes-values";
import { GetListAndProductIndexes } from "@modules/account/utils/edit-store-funcs/lists/get-product";
import { EditCommentRequestData } from "@modules/account/ts/types/edit-comment-request-data";
import { List } from "@modules/account/ts/types/list.type";

export function EditCommentDataOnProduct(
  items: List[],
  listId: string,
  productId: string,
  data: EditCommentRequestData
): Array<any> {
  const mass = items;

  const indexes: IndexesValues = GetListAndProductIndexes(
    mass,
    listId,
    productId
  );

  const product =
    mass[indexes.editListIndex].products[indexes.editProductIndex];

  for (const key in data) {
    product[key] = data[key];
  }
  return [...mass];
}
