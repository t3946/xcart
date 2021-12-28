import React from "react";
import ReactToPrint from "react-to-print";
import PrintIcon from "@modules/icon/components/account/print/PrintIcon";
import PictureAsPdfIcon from "@modules/icon/components/account/pdf/PictureAsPdfIcon";

interface IProps {
  componentRef: any;
  refund: any;
  transactionInfo: Record<any, any>;
}

export const TransactionItemTopBlock: React.FC<IProps> = (props: IProps) => {
  const { componentRef, refund, transactionInfo } = props;

  return (
    <div className="transaction-top-block-container">
      <div className="transaction-top-content-container">
        <div className="transaction-name-block">
          <div className="transaction-top-block-logo">
            <img
              src="/static/frontend/dist/images/icons/account/s3stores-logo.svg"
              alt={""}
            />
          </div>
          <div className="transaction-top-name-btns">
            <div className="transaction-name">
              {refund ? "REFUND" : "RECEIPT"}
              {` # ${transactionInfo.orderInfo.order_prefix}${transactionInfo.orderInfo.orderid}-${transactionInfo.orderInfo.order_type}`}
            </div>

            <div className="transaction-btns">
              <ReactToPrint
                trigger={() => (
                  <button className="form-button account-submit-btn-outline print-button transaction-button">
                    <div className="btn-entry">
                      <PrintIcon className="btn-icon" />
                      <div>PRINT</div>
                    </div>
                  </button>
                )}
                content={() => componentRef.current}
              />
              <ReactToPrint
                trigger={() => (
                  <button className="form-button account-submit-btn-outline transaction-button">
                    <div className="btn-entry">
                      <PictureAsPdfIcon className="btn-icon" />
                      <div>OPEN PDF</div>
                    </div>
                  </button>
                )}
                content={() => componentRef.current}
              />
            </div>
          </div>
        </div>
        <div className="transaction-top-info-container">
          <div className="transaction-top-info">
            <div className="transaction-top-info-left-part">
              <div className="transaction-top-info-left-part-label">
                S3 Stores Inc.
              </div>
              <p> 27 Joseph St.</p>
              <p>Chatham , Ontario N7L 3G5</p>
              <p>Canada</p>
              <div className="info-item-container">
                <p className="label-info-item">Tel:</p>
                <p>(616) 259-5711</p>
              </div>
              <div className="info-item-container">
                <p className="label-info-item">Fax:</p>
                <p> 1-800-929-2835</p>
              </div>
              <div className="info-item-container">
                <p className="label-info-item">Email:</p>
                <p>orders@s3stores.com</p>
              </div>
            </div>
            <div className="transaction-top-info-right-part">
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Order date:</p>
                <p className="left-part">orders@s3stores.com</p>
              </div>
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Order status:</p>
                <p className="left-part">orders@s3stores.com</p>
              </div>
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Payment method:</p>
                <p className="left-part">orders@s3stores.com</p>
              </div>
              <div className="info-item-container info-item-container-spacing">
                <p className="label-info-item right-part">Delivery methods:</p>
                <p className="left-part">orders@s3stores.com</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
