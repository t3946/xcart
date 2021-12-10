import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { getOrders } from "@client/jsx/redux/actions/account-actions/OrdersActions";
import { OrdersListHeader } from "@client/modules/account/components/orders/OrdersListHeader";
import { OrderItem } from "@client/modules/account/components/orders/OrderItem";
import { AccountStore } from "@client/modules/account/ts/types/store.type";
import { Spinner } from "react-bootstrap";

interface OrdersPageProps {
  label: string;
  type: string;
}

export const OrdersPage: React.FC<OrdersPageProps> = ({ label, type }) => {
  const dispatch = useDispatch();

  const orders = useSelector((e: AccountStore) => e.ordersStore.orders);

  const ordersLoading = useSelector(
    (e: AccountStore) => e.ordersStore.ordersLoading
  );

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
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      ) : (
        <div>
          {orders[type]?.items?.length ? (
            <div>
              {orders[type].items?.map((e) => {
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
