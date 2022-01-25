import React, { useEffect } from "react";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import Store from "@redux/stores/Store";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";
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
  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

  return (
    <div>
      <div className="page-label">Order actions</div>
      {breakpoint && (
        <>
          {breakpoint.lg && <OrderTabs orderItem={orderItem} />}
          {!breakpoint.lg && <OrderAccordion orderItem={orderItem} />}
        </>
      )}
    </div>
  );
};
