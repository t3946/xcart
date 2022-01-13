import React, { ReactElement, useEffect, useState } from "react";
import { OrderInfoHeader } from "@modules/account/components/orders/OrderInfoHeader";
import { OrderPageURLParams } from "@modules/account/ts/types/order-page-url-params.type";
import { AccountStore } from "@modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import { ApiService } from "@modules/shared/services/api.service";
import { CircularProgress } from "@material-ui/core";
import { setOrders } from "@redux/actions/account-actions/OrdersActions";

export const OrderInfoContainerPage: React.FC<any> = ({
  children,
  showMenu = true,
}) => {
  const urlParams = useParams<OrderPageURLParams>();

  const api = new ApiService();

  function getInfo() {
    return api.get(`/account/api/orders/get-one-order/${urlParams.id}`);
  }

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
          {showMenu && <OrderInfoHeader orderItem={orderItem} />}
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
