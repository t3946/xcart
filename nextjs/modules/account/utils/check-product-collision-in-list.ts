import { List } from "../ts/types/list.type";

export const checkProductCollisionInList = (
  fromList: List | null | undefined,
  toList: List | undefined,
  list_item_id: number
) => {
  if (toList) {
    const movingItem = fromList.items.find(
      (item) => item.list_item_id === list_item_id
    );
    if (movingItem?.productType === "product") {
      const inList = toList.items.find(
        (item) => item.product_id === movingItem.product_id
      );

      if (inList) {
        return false;
      }
    }
    return true;
  }
  return false;
};
