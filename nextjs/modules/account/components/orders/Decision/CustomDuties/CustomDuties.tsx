import React from "react";
import Checkbox from "@modules/ui/forms/Checkbox";
import { Formik, Form } from "formik";
import InnerPage from "@modules/account/components/shared/InnerPage";
import cn from "classnames";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/router";
import Alert from "@modules/account/components/shared/Alert";
import StoreInterface from "@modules/account/ts/types/store.type";
import { submitResponsibilityForCustomDutiesAction } from "@redux/actions/account-actions/DecisionsActions";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

import Styles from "@modules/account/components/orders/Decision/CustomDuties/CustomDuties.module.scss";

const CustomDuties: React.FC = () => {
  const initialValues = {
    agreement: false,
  };
  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);

  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();

  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  if (alert) {
    breakpoint({
      xs: function () {
        dispatch(setAlertAction(null));
        dispatch(setMobileAlertAction(alert));
        dispatch(showMobileAlertAction(true));
        dispatch(setVisibleShadowPanelAction(true));
        router.push("/orders/decisions-required");
      },
      md: function () {},
    });
  }

  const submit = (values, { setSubmitting }) => {
    setSubmitting(true);
    dispatch(
      submitResponsibilityForCustomDutiesAction({
        data: {},
        success(res) {
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Alert
              message!`,
            })
          );
          setTimeout(() => {
            setShow(false);
            dispatch(setAlertAction(null));
          }, 3000);
        },
      })
    );

    setShow(true);
    dispatch(
      setAlertAction({
        variant: "decisionSuccess",
        message: `Alert
              message!`,
      })
    );
    setTimeout(() => {
      setShow(false);
      dispatch(setAlertAction(null));
    }, 3000);
    //
  };

  return alert ? (
    <InnerPage
      hatClasses={cn(Styles.decision__hat, Styles.decisionHat)}
      headerClasses={Styles.decisionHeader}
      bodyClasses={Styles.decision_body}
      header={"Responsibility for custom duties"}
    >
      <Alert
        show={show}
        variant={alert.variant}
        message={alert.message}
        classes={{
          container: "pt-20 pb-5 pt-lg-0",
          alert: ["account-inner-page_alert"],
        }}
      />
    </InnerPage>
  ) : (
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

              <label
                className={cn(
                  Styles.checkbox__container,
                  Styles.checkboxContainer,
                  "d-block",
                  "cursor-pointer",
                  {
                    [Styles.checkboxContainer_active]: values.agreement,
                  }
                )}
              >
                <Checkbox
                  name="agreement"
                  checked={values.agreement}
                  onChange={handleChange}
                  disabled={isSubmitting}
                  label="I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada."
                />
              </label>

              <button
                type="submit"
                className={cn(
                  "form-button",
                  "w-100",
                  "w-md-auto",
                  "mx-auto",
                  "mx-lg-0",
                  Styles.button,
                  Styles.decision__button
                )}
                disabled={!values.agreement || isSubmitting}
              >
                Submit
              </button>
            </Form>
          );
        }}
      </Formik>
    </InnerPage>
  );
};

export default CustomDuties;
