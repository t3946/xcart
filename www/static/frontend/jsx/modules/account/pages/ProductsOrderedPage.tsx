import React from "react";
import { ProductsOrderedItem } from "@client/modules/account/components/orders/ProductsOrderedItem";

interface ProductsOrderedPageProps {
  orderItem;
}

export const ProductsOrderedPage: React.FC<ProductsOrderedPageProps> = ({
  orderItem,
}) => {
  return (
    <div>
      <div className="page-label">Products ordered</div>
      {orderItem.orderGroups.map((e) => {
        return <ProductsOrderedItem orderItem={e} />;
      })}
    </div>
  );
};
