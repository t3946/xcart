import React from 'react';
import MultiplePaymentItem from "@client/jsx/components/footer-payment-methods/MultiplePaymentItem";
import PaymentItem from "@client/jsx/components/footer-payment-methods/PaymentItem";

interface IProps {
  payments: Record<any, any>[];
}

export const FooterPaymentMethods: React.FC<IProps> = function (props) {
  const {payments} = props;
  const items = [];

  function getChildPayments(payment, payments) {
    const children = [];

    for (let i = 0; i < payments.length; i++) {
      if (payments[i].parent_id === payment.payment_method_id) {
        children.push(payments[i]);
      }
    }

    return children;
  }


  for (const payment of payments) {
    const children = getChildPayments(payment, payments);

    if (payment.parent_id !== null) {
      continue;
    }

    if (children.length) {
      items.push(
        <li className="footer-payment-method-item footer-payment-method_item">
          <MultiplePaymentItem paymentMethod={payment} paymentChildren={children} />
        </li>
      );
      continue;
    }

    items.push(
      <li className="footer-payment-method-item footer-payment-method_item">
        <PaymentItem paymentMethod={payment} />
      </li>
    );
  }


  return <ul className="footer-payment-methods footer_payment-methods no-bullet menu-list">
    {items}
  </ul>;
};

export default FooterPaymentMethods;
