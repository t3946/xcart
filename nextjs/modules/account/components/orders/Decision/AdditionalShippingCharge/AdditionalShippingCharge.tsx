import React from "react";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import InnerPage from "@components/common/inner-page/InnerPage";
import { Formik, Form } from "formik";
import cn from "classnames";
import { useDispatch } from "react-redux";
import Button from "@modules/ui/forms/Button";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import unpaidOrderStyles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import Styles from "@modules/account/components/orders/Decision/AdditionalShippingCharge/AdditionalShippingCharge.module.scss";
import PaymentSections from "@components/pages/decision/[id]/PaymentSections";
import CardHeader from "@modules/account/components/wallet/CardHeader";

const AdditionalShippingCharge: React.FC<any> = (props) => {
  const { decision, onChange, cards, defaultCardId, paypalUrl } = props;
  const dispatch = useDispatch();
  const initialValues = {
    paymentMethod: "debit",
    billingSameShipping: false,
    cardId: defaultCardId,
  };

  if (
    decision.solved &&
    ["card", "paypal"].indexOf(decision.options.method) !== -1
  ) {
    initialValues.paymentMethod = decision.options.method;
  }

  const columnPadding = ["px-2", "px-md-3", "px-lg-4"];

  const classes = {
    columnPadding,
    text: [
      Styles.pagePadding,
      Styles.pageText,
      Styles.page__text,
      columnPadding,
    ],
    shippingGrid: [
      "d-flex",
      "justify-content-between",
      "align-items-center",
      Styles.gridItemSublinesItem,
    ],
  };

  function submit(
    values,
    action: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);

      dispatch(
        solveDecisionAction({
          data: {
            decision_id: decision.decision_id,
            cardId: values.cardId,
            action,
          },

          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  function submitWithoutValidationOrder(
    action: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);

      const data: any = { decision_id: decision.decision_id, action };

      dispatch(
        solveDecisionAction({
          data,
          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  function paymentSectionTemplate() {
    if (decision.solved) {
      switch (decision.options.action) {
        case "pay-by-card":
          const card = decision.options.card;
          return (
            <>
              <p className={cn(classes.text)}>Paid by credit card.</p>
              <div className={cn(classes.text, Styles.decisionCaption)}>
                <CardHeader cardLast4={card.last4} cardType={card.brand} />
              </div>
            </>
          );
        case "pay-by-paypal":
          return <p className={cn(classes.text)}>Paid by paypal.</p>;
        case "cancel-order":
          return <p className={cn(classes.text)}>Order was canceled.</p>;
      }
      return null;
    }

    return (
      <>
        <p
          className={cn(
            Styles.pagePadding,
            Styles.pageCaption,
            "mb-lg-20",
            classes.columnPadding
          )}
        >
          <b>Please advise on how you would like to proceed with this order:</b>
        </p>

        <Formik initialValues={initialValues} onSubmit={submit}>
          {({ values, handleChange, isSubmitting, setSubmitting }) => {
            return (
              <Form>
                <GreyGrid
                  classes={{
                    item: [
                      Styles.gridItem_border_none,
                      Styles.gridPaymentItem,
                      "m-0",
                    ],
                    list: [
                      Styles.gridPayment,
                      Styles.page__gridPayment,
                      classes.columnPadding,
                    ],
                  }}
                  items={[
                    <ul className={Styles.list}>
                      <li className={Styles.gridPaymentItemCaption}>
                        If you would like to proceed and pay the difference,
                        please use the following form
                      </li>
                    </ul>,

                    <PaymentSections
                      cards={cards}
                      defaultCardId={defaultCardId}
                      checkedValue={values.paymentMethod}
                      handleChange={handleChange}
                      isSubmitting={isSubmitting}
                      setSubmitting={setSubmitting}
                      submit={submit}
                      values={values}
                      paypalUrl={paypalUrl}
                    />,
                  ]}
                />

                <GreyGrid
                  classes={{
                    item: [Styles.gridItem_border_none, "p-0", "m-0"],
                    list: [
                      Styles.page__gridCancelOrder,
                      Styles.gridPayment,
                      Styles.gridCancel,
                      classes.columnPadding,
                    ],
                  }}
                  items={[
                    <ul className={Styles.list}>
                      <li className={Styles.gridPaymentItemCaption}>
                        If you would like to cancel the order, press
                      </li>
                    </ul>,
                    <Button
                      className={cn(
                        "form-button",
                        "fw-bold",
                        "form-button__outline",
                        "w-md-auto",
                        "mx-auto",
                        "mt-20",
                        "mx-lg-0",
                        "mt-md-5",
                        "mt-lg-4",
                        unpaidOrderStyles.button
                      )}
                      onClick={submitWithoutValidationOrder(
                        "cancel-order",
                        setSubmitting
                      )}
                    >
                      Cancel order
                    </Button>,
                  ]}
                />
              </Form>
            );
          }}
        </Formik>
      </>
    );
  }

  return (
    <InnerPage
      hatClasses={Styles.hat}
      headerClasses={Styles.header}
      header="Additional shipping charge"
      bodyClasses={"p-0"}
    >
      <p className={cn(classes.text)}>
        While reviewing your order, we have found that the shipping estimate
        which our shipping quote server gave us was mistaken. This sometimes
        occurs when the product is oversized or somehow irregular in shape or
        weight.
      </p>

      <p className={cn(classes.text)}>We apologize for any inconvenience.</p>
      <p className={cn(classes.text, "mb-14", "mb-md-18", "mb-lg-20")}>
        We have manually recalculated the shipping cost for this order, and the
        actual shipping cost works out to{" "}
        <span className="d-inline-block">{"{{required}}"}</span>. The difference
        that needs to be paid to process the order is{" "}
        <span className="d-inline-block">{"{{additional}}"}</span>.
      </p>

      <GreyGrid
        items={[
          <>
            <div className={cn(classes.shippingGrid)}>
              <div>Actual shipping cost</div>{" "}
              <div>
                $ {parseFloat(decision.options.actualShippingCost).toFixed(2)}
              </div>
            </div>
            <div className={cn(classes.shippingGrid)}>
              <div>Shipping cost paid</div>{" "}
              <div>
                $ {parseFloat(decision.options.shippingCostPaid).toFixed(2)}
              </div>
            </div>
          </>,
          <div
            className={cn(
              "d-flex",
              "justify-content-between",
              "align-items-center",
              "fw-bold"
            )}
          >
            <div>Additional shipping charge</div>{" "}
            <div>
              ${" "}
              {parseFloat(decision.options.additionalShippingCharge).toFixed(2)}
            </div>
          </div>,
        ]}
        classes={{
          item: [
            Styles.gridItem,
            Styles.gridItem_border_none,
            "m-0",
            classes.columnPadding,
          ],
          list: [Styles.gridShipping, Styles.page__gridShipping],
        }}
      />

      {paymentSectionTemplate()}
    </InnerPage>
  );
};

export default AdditionalShippingCharge;
