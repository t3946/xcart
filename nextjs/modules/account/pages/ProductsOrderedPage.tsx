import React, { useEffect } from "react";
import { ProductsOrderedItem } from "@modules/account/components/orders/ProductsOrderedItem";
import Store from "@redux/stores/Store";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";

interface ProductsOrderedPageProps {
  orderItem: OrderView;
}

export const ProductsOrderedPage: React.FC<ProductsOrderedPageProps> = ({
  orderItem,
}) => {
  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);

  return (
    <div>
      <div className="page-label products-ordered-label">Products ordered</div>

      {orderItem.groups.map((group, i) => (
        <ProductsOrderedItem group={group} key={`product-${i}`} />
      ))}
    </div>
  );
};
