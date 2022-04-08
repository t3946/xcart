import React from "react";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { Form, Formik, FormikHelpers } from "formik";
import { useDispatch } from "react-redux";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import Button from "@modules/ui/forms/Button";

interface IProps {
  onChange: (message: string) => any;
  decision: DecisionsInterface;
  paypalUrl: string;
}

const CheckForPurchaseOrderShouldBeIssued: React.FC<IProps> = (
  props: IProps
) => {
  const dispatch = useDispatch();
  const { decision, onChange } = props;

  function submit(values: any, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
        },

        success() {
          actions.setSubmitting(false);
        },
      })
    );

    onChange("Decision solved");
  }

  return (
    <>
      <Formik initialValues={{}} onSubmit={submit}>
        {({ isSubmitting }) => {
          return (
            <Form>
              <h1
                className={cn([
                  "decision-inner-header",
                  Styles.decision__title,
                ])}
              >
                Check for Purchase Order should be issued to S3 Stores
              </h1>

              <p>
                Your PO is received and will be processed, however please make
                sure that check will be issued to S3 Stores, Inc.
              </p>

              <p>
                We notify you about it because your PO is issued to the name of
                our website, not to our company name which is S3 Stores, Inc.
              </p>

              {decision.solved === 0 && (
                <Button
                  className={"w-auto"}
                  type={"submit"}
                  disabled={isSubmitting}
                >
                  confirm understanding
                </Button>
              )}
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default CheckForPurchaseOrderShouldBeIssued;
