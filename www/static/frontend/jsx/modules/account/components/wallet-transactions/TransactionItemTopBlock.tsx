import React from "react";
import { Button } from "@material-ui/core";
import PrintIcon from "@material-ui/icons/Print";
import PictureAsPdfIcon from "@material-ui/icons/PictureAsPdf";
import ReactToPrint from "react-to-print";

export const TransactionItemTopBlock = ({
  componentRef,
  refund = undefined,
  transactionInfo,
}) => {
  return (
    <div className="transaction-top-block-container">
      <div className="transaction-top-content-container">
        <div className="transaction-name-block">
          <div className="transaction-top-block-logo">
            <img src="/static/frontend/dist/images/icons/account/s3stores-logo.svg" />
          </div>
          <div className="transaction-top-name-btns">
            <div className="transaction-name">
              {refund ? "REFUND" : "RECEIPT"}
              {` # ${transactionInfo.orderInfo.order_prefix}${transactionInfo.orderInfo.orderid}-${transactionInfo.orderInfo.order_type}`}
            </div>

            <div className="transaction-btns">
              <ReactToPrint
                trigger={() => (
                  <Button className="account-submit-btn-outline print-button transaction-button">
                    <div className="btn-entry">
                      <PrintIcon className="btn-icon" />
                      <div>PRINT</div>
                    </div>
                  </Button>
                )}
                content={() => componentRef.current}
              />
              <ReactToPrint
                trigger={() => (
                  <Button className="account-submit-btn-outline transaction-button">
                    <div className="btn-entry">
                      <PictureAsPdfIcon className="btn-icon" />
                      <div>OPEN PDF</div>
                    </div>
                  </Button>
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
