import React, { useEffect } from "react";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import Store from "@redux/stores/Store";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import { OrderTabs } from "@modules/account/components/order/order-tabs/OrderTabs";
import OrderAccordion from "@modules/account/components/order/order-accordion/OrderAccordion";

interface OrderActionsPage {
  orderItem: OrderView;
}

export const OrderActionsPage: React.FC<OrderActionsPage> = (props) => {
  const { orderItem } = props;
  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);

  return (
    <div>
      <div className="page-label">Order actions</div>
      <div className="d-none d-lg-block">
        <OrderTabs orderItem={orderItem} />
      </div>
      <div className="d-lg-none">
        <OrderAccordion orderItem={orderItem} />
      </div>
    </div>
  );
};
