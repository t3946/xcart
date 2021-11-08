import { IndexesValues } from "@client/modules/account/ts/types/get-indexes-values";
import { GetListAndProductIndexes } from "@client/modules/account/utils/edit-store-funcs/lists/get-product";
import { EditCommentRequestData } from "@client/modules/account/ts/types/edit-comment-request-data";
import { List } from "@client/modules/account/ts/types/list.type";

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
