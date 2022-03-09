import React from "react";

import Styles from "@components/common/payment-card-image/PaymentCardImage.module.scss";

interface IProps {
  logo?: string;
  name: string;
}

const PaymentCardImage: React.FC<IProps> = ({ logo, name }) => {
  return <img src={`/${logo}`} className={Styles.image} alt={name} />;
};

export default PaymentCardImage;
