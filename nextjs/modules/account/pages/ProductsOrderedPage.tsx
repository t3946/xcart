import React, { useEffect } from "react";
import { ProductsOrderedItem } from "@modules/account/components/orders/ProductsOrderedItem";
import Store from "@redux/stores/Store";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";

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
