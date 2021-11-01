import React, { ReactElement, useEffect, useState } from "react";
import { OrderInfoHeader } from "@client/modules/account/components/orders/OrderInfoHeader";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";
import { AccountStore } from "@client/modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import { useParams } from "react-router-dom";
import { ApiService } from "@client/modules/shared/services/api.service";
import { CircularProgress } from "@material-ui/core";
import { setOrders } from "@client/jsx/redux/actions/account-actions/OrdersActions";

export const OrderInfoContainerPage: React.FC = ({ children }) => {
  const urlParams = useParams<OrderPageURLParams>();

  const api = new ApiService();

  function getInfo() {
    return api.get(`/account/api/orders/get-one-order/${urlParams.id}`);
  }

  console.log("initial");

  const orderFromStore = useSelector((e: AccountStore) =>
    e.ordersStore?.orders[urlParams.orderType]?.items?.find(
      (e) => e?.orderInfo?.orderid === urlParams.id
    )
  );

  const [orderItem, setOrderItem] = useState(orderFromStore);

  const dispatch = useDispatch();
  useEffect(() => {
    if (orderItem) {
      return;
    }
    getInfo().then((e: any) => {
      setOrderItem(e.data);
      dispatch(setOrders([e.data], urlParams.orderType));
    });
  }, []);

  return (
    <div>
      {orderItem ? (
        <>
          <OrderInfoHeader orderItem={orderItem} />
          {React.Children.map(
            children,
            (child: ReactElement<{ orderItem: any }>) =>
              React.cloneElement(child, { orderItem })
          )}
        </>
      ) : (
        <div className="progress-circular">
          <CircularProgress classes={{ root: "circular-item" }} />
        </div>
      )}
    </div>
  );
};
