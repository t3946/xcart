import React from "react";
import cn from "classnames";
import OrderTrackingLine from "@modules/account/components/orders/OrderTrackingLine";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import Link from "next/link";
import { getInvoicePdf } from "@redux/actions/account-actions/OrdersActions";
import { useDispatch } from "react-redux";

import DashboardStyles from "@modules/account/components/dashboard/Dashboard.module.scss";
import Styles from "@modules/account/components/dashboard/OrderTracking.module.scss";

interface IProps {
  orderInfo: {
    order_prefix: string;
    orderid: number;
    groups: {
      order_group_id: number;
      statuses_history: {
        id: number;
        group_id: number;
        status: string;
        old_status: string;
        updated: string;
      }[];
      trackings: {
        id: number;
        tracknum: string;
        carrier: {
          link: string;
        };
      }[];
    }[];
  };
}

const OrderTracking: React.FC<IProps> = ({ orderInfo }) => {
  const dispatch = useDispatch();
  const [invoiceUrl, setInvoicePdf] = React.useState<string>("");

  React.useEffect(() => {
    dispatch(
      getInvoicePdf({
        data: {
          orderid: orderInfo.orderid,
        },
        success(res) {
          setInvoicePdf(res.data);
        },
      })
    );
  }, []);

  return (
    <RectangularButton
      classNames={{ container: [Styles.container, DashboardStyles.card] }}
      header={
        <div className={cn(Styles.header, "d-flex w-100 align-items-end")}>
          <div className={Styles.order}>
            <div className={Styles.orderNumber}>
              Order # {`${orderInfo.order_prefix}${orderInfo.orderid}`}
            </div>
          </div>
          <div className="d-none d-md-block text-center text-lg-start flex-grow-1">
            <Link
              href={`/order/[id]/order-tracking`}
              as={`/order/${orderInfo.orderid}/order-tracking`}
            >
              <a>
                <span className={Styles.textBlue}>View details</span>
              </a>
            </Link>
          </div>
          <div className="d-none d-md-inline">
            <a href={invoiceUrl} className={Styles.textBlue} target={"_blank"}>
              Invoice.pdf
            </a>
          </div>
        </div>
      }
      body={
        <div className="w-100">
          <div
            className={cn(
              Styles.orderTrack,
              "mt-14",
              "mt-md-10",
              "mb-18",
              "mt-lg-14"
            )}
          >
            <Link href={invoiceUrl}>
              <a
                className={cn(Styles.textBlue, "d-block float-right d-md-none")}
              >
                Invoice
              </a>
            </Link>
          </div>
          <div className={Styles.trackingList}>
            {orderInfo.groups.map((group, i) => (
              <div key={`${group.order_group_id}_${i}`}>
                <b>
                  {!!group.trackings.length &&
                    group.trackings.map((track, i) => (
                      <div key={`${track.id}_${i}`}>
                        Tracking number <br className="d-md-none" />
                        <a
                          target={"_blank"}
                          href={track.carrier.link.replace(
                            "{{tracknum}}",
                            track.tracknum
                          )}
                          className={Styles.textBlue}
                        >
                          {track.tracknum}
                        </a>
                      </div>
                    ))}
                </b>
                <OrderTrackingLine statuses={group.statuses_history} />
              </div>
            ))}
          </div>
        </div>
      }
      footer={
        <Link
          href={`/order/[id]/order-tracking`}
          as={`/order/${orderInfo.orderNumber}/order-tracking`}
        >
          <button
            className={cn(
              Styles.button,
              "mt-4",
              "d-md-none",
              "form-button",
              "form-button__outline",
              "fw-bold"
            )}
          >
            view details
          </button>
        </Link>
      }
    />
  );
};

export default OrderTracking;
