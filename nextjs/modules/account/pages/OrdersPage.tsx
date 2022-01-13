import React, { useEffect } from "react";
import { useDispatch } from "react-redux";
import { getOrders } from "@redux/actions/account-actions/OrdersActions";
import { OrdersListHeader } from "@modules/account/components/orders/OrdersListHeader";
import { OrderItem } from "@modules/account/components/orders/OrderItem";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { Spinner } from "react-bootstrap";

interface OrdersPageProps {
  label: string;
  type: string;
}

const OrdersPage: React.FC<OrdersPageProps> = ({ label, type }) => {
  const dispatch = useDispatch();

  const { orders, loading, selectDate } = useSelectorAccount(
    (store) => store.ordersStore
  );
  useEffect(() => {
    dispatch(getOrders(type));
  }, [selectDate]);
  return (
    <div>
      <OrdersListHeader
        orderType={type}
        selectValue={selectDate}
        label={label}
      />
      {loading ? (
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      ) : (
        <div>
          {orders?.length ? (
            <div>
              {orders.map((order) => (
                <OrderItem orderType={type} key={order.orderId} order={order} />
              ))}
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
