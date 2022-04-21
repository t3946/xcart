import React, {useEffect} from "react";
import Store from "@redux/stores/Store";
import {setBreakpoint} from "@redux/actions/account-actions/MainActions";
import {getBreakpointsFlags} from "@modules/account/hooks/useBreakpoint";
import {OrderView} from "@modules/account/ts/types/order/order-view.types";
import ShippingTable from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/ShippingTable";
import GrandTotalProductOrdered
  from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered";

interface ProductsOrderedPageProps {
  order: OrderView;
}

export const ProductsOrderedPage: React.FC<ProductsOrderedPageProps> = (
  props
) => {
  const { order } = props;

  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);

  return (
    <div>
      <div className="page-label products-ordered-label">Products ordered</div>

      {order.groups.map((group, i) => (
        <ShippingTable group={group} key={`product-${i}`} order={order} />
      ))}

      <GrandTotalProductOrdered order={order} />
    </div>
  );
};
