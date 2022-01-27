import { EditCommentRequestData } from "@modules/account/ts/types/edit-comment-request-data";
import { List } from "@modules/account/ts/types/list.type";

export const editCommentDataProduct = (
  state: List,
  productId: number,
  data: EditCommentRequestData
): List => ({
  ...state,
  products: state.products.map((product) => {
    if (product.productId === productId) {
      return { ...product, ...data };
    }
    return product;
  }),
});
