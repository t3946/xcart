import React from "react";

export const TransactionItemsListTotal = ({ orderInfo }) => {
  return (
    <div className="transaction-total-container">
      <div className="total-left-side">
        <div className="info-item-container">
          <p className="label-info-item right-part">Payment status:</p>
          <p className="left-part">{orderInfo.a2b_status}</p>
        </div>
        <div className="info-item-container">
          <p className="label-info-item right-part">Shipping status:</p>
          <p className="left-part">{orderInfo.a2c_status}</p>
        </div>
      </div>
      <div className="total-group-right-side">
        <div className="info-item-container info-item-container-spacing regular">
          <p className=""> Regular shipping:</p>
          <p className="">US$ {orderInfo.shipping_gross}</p>
        </div>
        <div className="info-item-container info-item-container-spacing tax">
          <div className="">Sales Tax:</div>
          <div className="">US$ {orderInfo.total_pst}</div>
        </div>
        <div className="info-item-container info-item-container-spacing tax">
          <p className="">VAT Tax: </p>
          <p className="">US$ {orderInfo.total_tax}</p>
        </div>
        <div className="info-item-container info-item-container-spacing subtotal">
          <p className="">Subtotal:</p>
          <p className="">US$ {orderInfo.total_gross}</p>
        </div>
      </div>
    </div>
  );
};
