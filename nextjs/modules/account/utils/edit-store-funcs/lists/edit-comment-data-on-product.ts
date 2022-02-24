import { EditCommentRequestData } from "@modules/account/ts/types/edit-comment-request-data";
import { List } from "@modules/account/ts/types/list.type";

export const editCommentDataProduct = (
  state: List,
  list_items_id: number,
  data: EditCommentRequestData
): List => ({
  ...state,
  products: state.products.map((product) => {
    if (product.list_items_id === list_items_id) {
      return { ...product, ...data };
    }
    return product;
  }),
});
