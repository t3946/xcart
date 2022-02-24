import React from "react";

export const TransactionAddresses = ({ refund = undefined, order }) => {
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
            {order.s_firstname + " "}
            {order.s_lastname && order.s_lastname}
          </p>
        </div>

        {order.s_company && (
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Company:</p>
            <p className="left-part"> {order.s_company}</p>
          </div>
        )}

        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Address:</p>
          <p className="left-part"> {order.s_address}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">City:</p>
          <p className="left-part"> {order.s_city}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">State/Province:</p>
          <p className="left-part"> {order.s_state}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part"> Zip/Postal Code:</p>
          <p className="left-part"> {order.s_zipcode}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Country:</p>
          <p className="left-part">{order.s_country}</p>
        </div>
      </div>
      <div className="transaction-billing-address">
        <div className="transaction-top-info-left-part-label">
          Billing address
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Full Name:</p>
          <p className="left-part">
            {order.b_firstname + " "}
            {order.b_lastname && order.b_lastname}
          </p>
        </div>

        {order.b_company && (
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Company:</p>
            <p className="left-part"> {order.b_company}</p>
          </div>
        )}

        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Address:</p>
          <p className="left-part"> {order.b_address}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">City:</p>
          <p className="left-part"> {order.b_city}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">State/Province:</p>
          <p className="left-part"> {order.b_state}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part"> Zip/Postal Code:</p>
          <p className="left-part"> {order.b_zipcode}</p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Country:</p>
          <p className="left-part">{order.b_country}</p>
        </div>
      </div>
    </div>
  );
};
