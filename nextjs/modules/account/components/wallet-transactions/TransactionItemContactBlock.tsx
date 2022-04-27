import React from "react";

export const TransactionItemContactBlock = ({ refund = false, order }) => {
  if (!order.firstname && !order.lastname && !order.phone && !order.email) {
    return null;
  }

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

        {(order.firstname || order.lastname) && (
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Full Name:</p>
            <p className="left-part">
              {[order.firstname, order.lastname].join(" ")}
            </p>
          </div>
        )}

        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Phone:</p>
          <p className="left-part">
            {order.phone}
            {order.phone_ext && " ext " + order.phone_ext}
          </p>
        </div>
        <div className="info-item-container info-item-container-spacing">
          <p className="label-info-item right-part">Email:</p>
          <p className="left-part"> {order.email}</p>
        </div>
      </div>
    </div>
  );
};
