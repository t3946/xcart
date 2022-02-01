import React from "react";
import { GroupRelatedItem } from "@admin/modules/order-fraud/ts/types/redux";
import { RelatedOrderItems } from "@admin/modules/order-fraud/components/related-orders-section/related-order-item/RelatedOrderItems";
interface RelatedGroupItem {
  group: GroupRelatedItem;
}
export const RelatedGroupItem: React.FC<RelatedGroupItem> = ({ group }) => {
  return group.products.map((product) => (
    <tr>
      <td />
      <td className="product-column">{product.name}</td>
      <RelatedOrderItems orders={product.orders} isFraud />
      <RelatedOrderItems orders={product.orders} />
    </tr>
  ));
};
