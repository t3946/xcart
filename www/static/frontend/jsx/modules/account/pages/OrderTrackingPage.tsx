import React, { useEffect, useState } from "react";
import { ApiService } from "@client/modules/shared/services/api.service";
import { OrderTrackingGroup } from "@client/modules/account/components/orders/OrderTrackingGroup";

interface OrderTrackingPageProps {
  orderItem?: any;
}

export const OrderTrackingPage: React.FC<OrderTrackingPageProps> = ({
  orderItem,
}) => {
  useEffect(() => {
    api
      .get(
        `https://nominatim.openstreetmap.org/search.php?street=${orderItem.orderInfo.s_address}&city=${orderItem.orderInfo.s_city}&state=${orderItem.orderInfo.s_state}&postalcode=${orderItem.orderInfo.s_zipcode}&polygon_geojson=1&format=jsonv2`
      )
      .then((e) => setShippingPos([e[0].lat, e[0].lon]))
      .catch((e) => console.log(e));
  }, []);

  const [shippingPos, setShippingPos] = useState(null);

  const api = new ApiService();
  console.log(orderItem);

  return (
    <div>
      <div className="page-label">Order tracking</div>
      {orderItem.orderGroups.map((group) => (
        <OrderTrackingGroup
          shippingPos={shippingPos}
          orderItem={orderItem}
          orderGroupInfo={group}
        />
      ))}
      <div className="order-tracking-container order-tracking-footer">
        <p>
          <b>Payment status: </b>
          <span>{orderItem.orderInfo.payment_status}</span>
        </p>
        <div>
          <b>Payment date: </b>
          <span>August 12th, 2021</span>
        </div>
      </div>
    </div>
  );
};
