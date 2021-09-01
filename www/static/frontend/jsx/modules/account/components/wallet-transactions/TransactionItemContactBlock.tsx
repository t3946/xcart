import React from "react";

export const TransactionItemContactBlock = ({
  refund = undefined,
  orderInfo,
}) => {
  return (
    <div
      className={`transaction-contact-info-container ${
        refund && "transaction-contact-info-refund-container"
      }`}
    >
      <div className="transaction-contact-info">
        <div className="transaction-top-info-left-part-label">
          Contact information
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Full Name:</p>
          <p className="left-part">
            {orderInfo.firstname + " "}
            {orderInfo.lastname && orderInfo.lastname}
          </p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Phone:</p>
          <p className="left-part">
            {orderInfo.phone}
            {orderInfo.phone_ext && " ext " + orderInfo.phone_ext}
          </p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Email:</p>
          <p className="left-part"> {orderInfo.email}</p>
        </div>
      </div>
    </div>
  );
};
