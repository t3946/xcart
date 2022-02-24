import React from "react";

export const TransactionAddresses = ({ refund = undefined, orderInfo }) => {
  return (
    <div
      className={`transaction-addresses-container ${
        refund && "transaction-addresses-refund-container"
      }`}
    >
      <div className="transaction-shipping-address">
        <div className="transaction-top-info-left-part-label">
          Shipping address
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Full Name:</p>
          <p className="left-part">
            {orderInfo.s_firstname + " "}
            {orderInfo.s_lastname && orderInfo.s_lastname}
          </p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Company:</p>
          <p className="left-part"> {orderInfo.s_company}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Address:</p>
          <p className="left-part"> {orderInfo.s_address}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">City:</p>
          <p className="left-part"> {orderInfo.s_city}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">State/Province:</p>
          <p className="left-part"> {orderInfo.s_state}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part"> Zip/Postal Code:</p>
          <p className="left-part"> {orderInfo.s_zipcode}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Country:</p>
          <p className="left-part">{orderInfo.s_country}</p>
        </div>
      </div>
      <div className="transaction-billing-address">
        <div className="transaction-top-info-left-part-label">
          Billing address
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Full Name:</p>
          <p className="left-part">
            {orderInfo.b_firstname + " "}
            {orderInfo.b_lastname && orderInfo.b_lastname}
          </p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Company:</p>
          <p className="left-part"> {orderInfo.b_company}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Address:</p>
          <p className="left-part"> {orderInfo.b_address}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">City:</p>
          <p className="left-part"> {orderInfo.b_city}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">State/Province:</p>
          <p className="left-part"> {orderInfo.b_state}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part"> Zip/Postal Code:</p>
          <p className="left-part"> {orderInfo.b_zipcode}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Country:</p>
          <p className="left-part">{orderInfo.b_country}</p>
        </div>
      </div>
    </div>
  );
};
