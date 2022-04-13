import { List } from "@modules/account/ts/types/list.type";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";

export const editIdeaName = (
  listView: List,
  productId: number,
  name: string
): List => ({
  ...listView,
  products: listView.products.map((product) => {
    if (
      product.list_idea_id == productId &&
      product.product_type === ListItemTypeEnum.IDEA
    ) {
      product.idea.name = name;
    }
    return product;
  }),
});
