import React from "react";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import InnerPage from "@components/common/inner-page/InnerPage";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import { Formik, Form } from "formik";
import cn from "classnames";
import * as yup from "yup";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useDispatch } from "react-redux";
import { CardElement } from "@stripe/react-stripe-js";
import AddCreditCardButton from "@components/pages/wallet/AddCreditCardButton";
import Button from "@modules/ui/forms/Button";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import unpaidOrderStyles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/AdditionalShippingCharge/AdditionalShippingCharge.module.scss";

const AdditionalShippingCharge: React.FC<any> = (props) => {
  const { decision, onChange } = props;
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const [createPaymentMethod, setCreatePaymentMethod] = React.useState(null);
  const [elements, setElements] = React.useState(null);
  const initialValues = {
    paymentMethod: "card",
    cardholderName: "",
    stripe: "",
    billingSameShipping: false,
  };

  if (
    decision.solved &&
    ["card", "paypal"].indexOf(decision.options.method) !== -1
  ) {
    initialValues.paymentMethod = decision.options.method;
  }

  const validationSchema = yup.object().shape({
    paymentMethod: yup.string(),
    cardholderName: yup
      .string()
      .max(40, "Max length is 40 character")
      .min(2, "Min length is 2 character")
      .required("Cardholder name is a required field"),
  });

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

  async function submit(values: Record<any, any>, actions: Record<any, any>) {
    actions.setSubmitting(true);

    if (values.paymentMethod === "debit") {
      const { error, paymentMethod } = await stripe.createPaymentMethod({
        type: "card",
        card: elements.getElement(CardElement),
      });

      if (error) {
        actions.setErrors({ stripe: error.message });
        return;
      }

      const form = {
        id: paymentMethod.id,
        billing_details: {
          address: {
            city: "New York",
            country: "US",
            line1: "asdsadasd",
            line2: "asdasdasd",
            postal_code: "61000",
            state: "NY",
          },
          email: user.email,
          name: user.public_name,
          phone: user.phone,
        },
        decision: decision,
      };

      dispatch();
    }
  }

  function submitWithoutValidationOrder(
    method: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);

      const data: any = { decision_id: decision.decision_id, method };

      if (method === "credit") {
        data.card_id = 1;
      }

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
          list: [
            Styles.gridShipping,
            Styles.page__gridShipping,
            { "mb-0": decision.solved },
          ],
        }}
      />

      {!decision.solved && (
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
      )}

      {!decision.solved && (
        <Formik initialValues={initialValues} onSubmit={submit}>
          {({
            values,
            handleChange,
            isSubmitting,
            setSubmitting,
            errors,
            setErrors,
            touched,
          }) => {
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

                    <PaymentSelection
                      classes={Styles.payment}
                      //for formik
                      checkedValue={values.paymentMethod}
                      onChange={handleChange}
                      disabled={isSubmitting || decision.solved}
                      name="paymentMethod"
                      integrated
                      options={[
                        {
                          label:
                            "Pay by Credit / Debit card, Apple Pay and Google Pay",
                          caption:
                            "Secure Visa, MasterCard, and AmEx payment through our secure server.",
                          value: "card",
                          template: (
                            <>
                              <AddCreditCardButton
                                classes={{ button: "w-auto" }}
                              />
                              <Button
                                className={"w-auto mt-3"}
                                onClick={submitWithoutValidationOrder(
                                  "credit",
                                  setSubmitting
                                )}
                                disabled={isSubmitting}
                              >
                                pay by credit
                              </Button>
                            </>
                          ),
                        },
                        {
                          label: "Pay by PayPal Balance",
                          caption:
                            "Secure payment by PayPal Balance (click Create an Account to also access VISA, MC, AmEx, and Discover payments).",
                          value: "paypal",
                          template: (
                            <div className="w-100">
                              <p
                                className={cn([
                                  paymentItemStyles.paymentItemCaption,
                                  paymentItemStyles.paymentItemCaption_accent,
                                ])}
                              >
                                You will be transferred to PayPal website to
                                complete your payment.
                              </p>

                              <div
                                className={
                                  "d-flex justify-content-center justify-content-lg-start"
                                }
                              >
                                <button
                                  type={"button"}
                                  disabled={isSubmitting}
                                  onClick={submitWithoutValidationOrder(
                                    "paypal",
                                    setSubmitting
                                  )}
                                  className={cn([
                                    "form-button",
                                    unpaidOrderStyles.button,
                                  ])}
                                >
                                  Pay by PayPal
                                </button>
                              </div>
                            </div>
                          ),
                        },
                      ]}
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
      )}
    </InnerPage>
  );
};

export default AdditionalShippingCharge;
