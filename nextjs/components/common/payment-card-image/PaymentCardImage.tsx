import React from "react";
import Styles from "@components/common/payment-card-image/PaymentCardImage.module.scss";

interface IProps {
  logo?: string;
  name: string;
  title?: string;
}

const PaymentCardImage: React.FC<IProps> = ({ logo, name, title }) => {
  return (
    <img src={`/${logo}`} className={Styles.image} alt={name} title={title} />
  );
};

export default PaymentCardImage;
