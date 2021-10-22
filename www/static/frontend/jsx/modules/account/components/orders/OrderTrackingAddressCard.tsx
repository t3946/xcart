import React from "react";

interface OrderTrackingAddressCardProps {
  logo: string;
  title: string;
  text: string;
  onClick: () => void;
}

export const OrderTrackingAddressCard: React.FC<OrderTrackingAddressCardProps> =
  ({ logo, text, title, onClick }) => {
    return (
      <div
        onClick={onClick}
        className="order-tracking-container address-card-container"
      >
        <img src={logo} className="address-card-img" />
        <div className="address-card-title">{title}</div>
        <div className="address-card-text">{text}</div>
      </div>
    );
  };
