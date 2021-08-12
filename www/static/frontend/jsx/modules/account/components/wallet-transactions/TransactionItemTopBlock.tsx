import React from "react";
import { Button } from "@material-ui/core";
import PrintIcon from "@material-ui/icons/Print";
import PictureAsPdfIcon from "@material-ui/icons/PictureAsPdf";

export const TransactionItemTopBlock = () => {
  return (
    <div className="transaction-top-block-container">
      <div className="transaction-top-block-logo">
        <img src="/static/frontend/dist/images/icons/account/s3stores-logo.svg" />
      </div>
      <div className="transaction-top-content-container">
        <div className="transaction-name-block">
          <div className="transaction-name">REFUND # KS-180043-HYP </div>
          <div className="transaction-btns">
            <Button className="account-submit-btn-outline print-button transaction-button">
              <div className="btn-entry">
                <PrintIcon className="btn-icon" />
                <div>PRINT</div>
              </div>
            </Button>
            <Button className="account-submit-btn-outline transaction-button">
              <div className="btn-entry">
                <PictureAsPdfIcon className="btn-icon" />
                <div>OPEN PDF</div>
              </div>
            </Button>
          </div>
        </div>
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
              <p className="label-info-item right-part">Email:</p>
              <p className="left-part">orders@s3stores.com</p>
            </div>
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Email:</p>
              <p className="left-part">orders@s3stores.com</p>
            </div>
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Email:</p>
              <p className="left-part">orders@s3stores.com</p>
            </div>
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Email:</p>
              <p className="left-part">orders@s3stores.com</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
