import React from "react";

interface OrderLogPageProps {}

export const OrderLogPage: React.FC<OrderLogPageProps> = () => {
  return (
    <div>
      <div className="page-label">Order log</div>
      <div className="order-log-header">
        <div className="order-log-date-cell">Date</div>
        <div className="order-log-name-cell">Name</div>
        <div className="order-log-type-cell">Type</div>
        <div className="order-log-action-cell">Action</div>
      </div>
      <div className="order-log-item">
        <div className="order-log-date-cell">25-Aug-2021 09:55:35</div>
        <div className="order-log-name-cell">Fast Freddy</div>
        <div className="order-log-type-cell">Robot</div>
        <div className="order-log-action-cell">
          Tracking number has been obtained from the warehouse
        </div>
      </div>
      <div className="order-log-item order-log-item-user">
        <div className="order-log-date-cell">25-Aug-2021 09:55:35</div>
        <div className="order-log-name-cell">Fast Freddy</div>
        <div className="order-log-type-cell">Robot</div>
        <div className="order-log-action-cell">
          Tracking number has been obtained from the warehouse
        </div>
      </div>
      <div className="order-log-item order-log-item-customer-service">
        <div className="order-log-date-cell">25-Aug-2021 09:55:35</div>
        <div className="order-log-name-cell">Fast Freddy</div>
        <div className="order-log-type-cell">Robot</div>
        <div className="order-log-action-cell">
          Tracking number has been obtained from the warehouse
        </div>
      </div>
      <div className="order-log-item order-log-item-user">
        <div className="order-log-date-cell">25-Aug-2021 09:55:35</div>
        <div className="order-log-name-cell">Fast Freddy</div>
        <div className="order-log-type-cell">Robot</div>
        <div className="order-log-action-cell">
          Tracking number has been obtained from the warehouse
        </div>
      </div>
    </div>
  );
};
