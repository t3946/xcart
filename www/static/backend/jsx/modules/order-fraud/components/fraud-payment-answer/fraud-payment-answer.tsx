import React, { Fragment } from "react";
import { FraudTableQuestion } from "@admin/modules/order-fraud/components/fraud-table-question/fraud-table-question";
import { PaymentAnswer } from "@admin/modules/order-fraud/ts/types/answer";
interface FraudPaymentAnswer {
  answer: PaymentAnswer;
}
export const FraudPaymentAnswer: React.FC<FraudPaymentAnswer> = ({
  answer,
}) => {
  return (
    <Fragment>
      {answer.general_payment && (
        <div className="table-wrapper__fraud-check-question">
          <FraudTableQuestion
            nameTable="payment"
            title="Payment Processor checks: General checks"
            listAnswer={answer.general_payment}
          />
        </div>
      )}
      {answer.stripe.length !== 0 && (
        <div className="table-wrapper__fraud-check-question">
          <FraudTableQuestion
            title="Payment Processor checks: Stripe specific checks"
            nameTable="payment"
            listAnswer={answer.stripe}
          />
        </div>
      )}
      {answer.pay_pal.length !== 0 && (
        <div className="table-wrapper__fraud-check-question">
          <FraudTableQuestion
            title="Payment Processor checks: PayPal specific checks"
            nameTable="payment"
            listAnswer={answer.pay_pal}
          />
        </div>
      )}
    </Fragment>
  );
};
