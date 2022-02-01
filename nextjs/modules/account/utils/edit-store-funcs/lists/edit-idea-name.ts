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
      product.productId == productId &&
      product.productType === ListItemTypeEnum.IDEA
    ) {
      product.product.name = name;
    }
    return product;
  }),
});
