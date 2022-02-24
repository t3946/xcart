import React, { useEffect } from "react";
import { ProductsOrderedItem } from "@client/modules/account/components/orders/ProductsOrderedItem";
import Store from "@client/jsx/redux/stores/Store";
import { setBreakpoint } from "@client/jsx/redux/actions/account-actions/MainActions";
import { getBreakpointsFlags } from "@client/modules/account/hooks/useBreakpoint";

interface ProductsOrderedPageProps {
  orderItem?: any;
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
      {orderItem.orderGroups.map((e) => {
        return <ProductsOrderedItem orderItem={e} />;
      })}
    </div>
  );
};
