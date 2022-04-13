import { List } from "@modules/account/ts/types/list.type";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";

export const editIdeaName = (
  currentList: List,
  productId: number,
  name: string
): List => ({
  ...currentList,
  items: currentList.items.map((listItem) => {
    if (
      listItem.list_idea_id == productId &&
      listItem.product_type === ListItemTypeEnum.IDEA
    ) {
      listItem.idea.name = name;
    }
    return listItem;
  }),
});
