import React from "react";
import { Form, Formik, FormikHelpers } from "formik";
import InnerPage from "@components/common/inner-page/InnerPage";
import cn from "classnames";
import { useDispatch } from "react-redux";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import HighlightCheckbox from "@modules/account/components/orders/Decision/CustomDuties/HighlightCheckbox";
import Styles from "@modules/account/components/orders/Decision/CustomDuties/CustomDuties.module.scss";
import Button from "@modules/ui/forms/Button";

const CustomDuties: React.FC<any> = (props) => {
  const { decision, onChange } = props;
  const initialValues = {
    agreement: false,
  };
  const dispatch = useDispatch();

  async function submit(values: Record<any, any>, helpers: FormikHelpers<any>) {
    helpers.setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
        },
        success() {
          helpers.setSubmitting(false);
        },
      })
    );

    onChange("Decision solved");
  }

  return (
    <InnerPage
      hatClasses={cn(Styles.decision__hat, Styles.decisionHat)}
      headerClasses={Styles.decisionHeader}
      bodyClasses={Styles.decision_body}
      header={"Responsibility for custom duties"}
      footer={
        <p className={cn(Styles.decisionCaption, "mb-20")}>
          PS. This online calculator can help you to estimate the final cost of
          bringing goods from the USA to Canada:
          <br />
          <a
            className={Styles.link}
            href="https://www.crossbordershopping.ca/calculators/canadian-duty-calculator"
            target={"_blank"}
          >
            https://www.crossbordershopping.ca/calculators/canadian-duty-calculator
          </a>
        </p>
      }
    >
      <Formik initialValues={initialValues} onSubmit={submit}>
        {({ values, handleChange, isSubmitting }) => {
          return (
            <Form>
              <p
                className={cn(
                  Styles.decisionCaption,
                  Styles.decision__caption1
                )}
              >
                Your order will be shipped from USA to Canada by <b>UPS</b> or{" "}
                <b>Fedex</b>.
              </p>

              <p className={cn(Styles.decisionCaption)}>
                Please confirm that you agree to be responsible for custom
                duties, CODs, and other charges associated with bringing goods
                to Canada.
              </p>

              <HighlightCheckbox
                className={Styles.checkbox__container}
                onChange={handleChange}
                checked={values.agreement || !!decision.solved}
                disabled={isSubmitting || !!decision.solved}
                label="I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada."
              />

              <Button
                type="submit"
                className={cn(
                  "form-button",
                  "w-100",
                  "w-md-auto",
                  "mx-auto",
                  "mx-lg-0",
                  Styles.button,
                  Styles.decision__button,
                  { "d-none": decision.solved }
                )}
                disabled={!values.agreement || isSubmitting}
              >
                Submit
              </Button>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default CustomDuties;
