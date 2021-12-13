import React, { useEffect } from "react";
import { useDispatch } from "react-redux";
import { getOrders } from "@redux/actions/account-actions/OrdersActions";
import { OrdersListHeader } from "@modules/account/components/orders/OrdersListHeader";
import { OrderItem } from "@modules/account/components/orders/OrderItem";
import { CircularProgress } from "@material-ui/core";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface OrdersPageProps {
  label: string;
  type: string;
}

const OrdersPage: React.FC<OrdersPageProps> = (props: OrdersPageProps) => {
  const { label, type } = props;
  const dispatch = useDispatch();

  const orders = useSelectorAccount((e) => e.ordersStore.orders);

  const ordersLoading = useSelectorAccount((e) => e.ordersStore.ordersLoading);

  useEffect(() => {
    dispatch(getOrders(type));
  }, [orders[type].selectValue]);

  return (
    <div>
      <OrdersListHeader
        orderType={type}
        selectValue={orders[type].selectValue}
        label={label}
      />
      {ordersLoading ? (
        <div className="progress-circular">
          <CircularProgress classes={{ root: "circular-item" }} />
        </div>
      ) : (
        <div>
          {orders[type]?.items?.length ? (
            <div>
              {orders[type].items?.map((e: any) => {
                return (
                  <OrderItem
                    orderType={type}
                    key={e?.orderInfo?.orderid}
                    order={e}
                  />
                );
              })}
            </div>
          ) : (
            <div className="no-items-block-container">
              <img
                className="no-items-block-img"
                src="/static/frontend/images/icons/account/no-items.svg"
              />
              <div className={"no-items-block-text"}>
                No orders for this time period
              </div>
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default OrdersPage;
