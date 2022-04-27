import React, { useEffect, useState } from "react";
import { ApiService } from "@modules/shared/services/api.service";
import { OrderTrackingGroup } from "@modules/account/components/orders/OrderTrackingGroup";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import Store from "@redux/stores/Store";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import moment from "moment";
import { useRouter } from "next/router";
import cn from "classnames";

interface OrderTrackingPage {
  order: OrderView;
}

export const OrderTrackingPage: React.FC<OrderTrackingPage> = ({ order }) => {
  const router = useRouter();
  if (["D", "A", "F"].includes(order.cb_status)) {
    router.push(`/order/${order.orderId}/products-ordered`);
    return null;
  }

  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
    api
      .get(
        `https://nominatim.openstreetmap.org/search.php?city=${order.s_city}&state=${order.s_state}&postalcode=${order.s_zipcode}&polygon_geojson=1&format=jsonv2`
      )
      .then((e) => {
        if (!e[0]) {
          return;
        }

        setShippingPos([e[0].lat, e[0].lon]);
      })
      .catch((e) => console.log(e));
  }, []);

  const [shippingPos, setShippingPos] = useState([]);
  const api = new ApiService();
  return (
    <div>
      <div className="page-label">Order tracking</div>
      {order.groups.map((group, i) => (
        <OrderTrackingGroup
          shippingPos={shippingPos}
          orderItem={order}
          orderGroupInfo={group}
          key={`map-${i}`}
        />
      ))}
      <div className="order-tracking-container order-tracking-footer">
        {!!order.cb_status && (
          <div className={cn({ "mb-3": !!order.date })}>
            <b>Payment status: </b>
            <span>{order.cb_status_model.name}</span>
          </div>
        )}

        {!!order.date && (
          <div>
            <b>Payment date: </b>
            <span>{moment.unix(order.date).format("LL")}</span>
          </div>
        )}
      </div>
    </div>
  );
};
