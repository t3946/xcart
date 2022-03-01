import React from "react";
import ReactToPrint from "react-to-print";
import PrintIcon from "@modules/icon/components/account/print/PrintIcon";
import PictureAsPdfIcon from "@modules/icon/components/account/pdf/PictureAsPdfIcon";
import Button, { ETheme } from "@modules/ui/forms/Button";

interface IProps {
  componentRef: any;
  refund: any;
  order: Record<any, any>;
}

export const TransactionItemTopBlock: React.FC<IProps> = (props: IProps) => {
  const { componentRef, refund, order } = props;

  const date = new Date(Number(order.date * 1000)).toLocaleDateString("en-EN", {
    month: "long",
    day: "2-digit",
    year: "numeric",
  });

  return (
    <div className="transaction-top-block-container">
      <div className="transaction-top-content-container">
        <div className="transaction-name-block d-flex flex-wrap flex-xl-nowrap mt-20">
          <div className="transaction-top-block-logo m-0 order-1 order-md-0 flex-shrink-0">
            <img
              src="/static/frontend/dist/images/icons/account/s3stores-logo.svg"
              alt={""}
            />
          </div>
          <div className="mb-20  mt-xl-0 order-0 transaction-top-name-btns w-100 justify-content-between flex-wrap flex-md-nowrap">
            <div className="transaction-name">
              {refund ? "REFUND" : "RECEIPT"}
              {` # ${order.order_prefix}${order.orderid}`}
            </div>

            <div className="d-none d-lg-flex">
              <div className={"w-50 me-2"}>
                <ReactToPrint
                  trigger={() => (
                    <Button theme={ETheme.outlined}>
                      <div className="btn-entry">
                        <PrintIcon className="me-2" />
                        <div>PRINT</div>
                      </div>
                    </Button>
                  )}
                  content={() => componentRef.current}
                />
              </div>
              <div className={"w-50 ms-2"}>
                <ReactToPrint
                  trigger={() => (
                    <Button theme={ETheme.outlined}>
                      <div className="d-flex align-items-center">
                        <PictureAsPdfIcon className="me-2" />
                        <div>OPEN PDF</div>
                      </div>
                    </Button>
                  )}
                  content={() => componentRef.current}
                />
              </div>
            </div>
          </div>
        </div>
        <div className="transaction-top-info-container">
          <div className="d-none d-xl-block transaction-top-block-logo" />
          <div className="col transaction-top-info">
            <div className="transaction-top-info-left-part">
              <div className="transaction-top-info-left-part-label">
                S3 Stores Inc.
              </div>
              <p> 27 Joseph St.</p>
              <p>Chatham , Ontario N7L 3G5</p>
              <p>Canada</p>
              <div>
                <p>
                  <b>Tel:</b> (616) 259-5711
                </p>
              </div>
              <div>
                <p>
                  <b>Fax:</b> 1-800-929-2835
                </p>
              </div>
              <div>
                <p>
                  <b>Email:</b>{" "}
                  <a href="mailto:orders@s3stores.com"> orders@s3stores.com</a>
                </p>
              </div>
            </div>
            <div className="transaction-top-info-right-part">
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Order date:</p>
                <p className="left-part">{date}</p>
              </div>
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Order status:</p>
                <p className="left-part">{order.status.name}</p>
              </div>
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Payment method:</p>
                <p className="left-part">{order.payment_method}</p>
              </div>
              {order.deliveryMethods && (
                <div className="info-item-container info-item-container-spacing">
                  <p className="label-info-item right-part">
                    Delivery methods:
                  </p>
                  <p className="left-part">{order.deliveryMethods}</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
