import { List } from "../ts/types/list.type";

export const checkProductCollisionInList = (
  fromList: List | null | undefined,
  toList: List | undefined,
  list_items_id: number
) => {
  if (toList) {
    const movingItem = fromList.products.find(
      (item) => item.list_items_id === list_items_id
    );
    if (movingItem?.productType === "product") {
      const inList = toList.products.find(
        (product) => product.productId === movingItem.productId
      );
      if (inList) {
        return false;
      }
    }
    return true;
  }
  return false;
};
