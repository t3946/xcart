import React from "react";
import CardOr from "@modules/ui/CardOr";
import { Formik, Form, FormikHelpers } from "formik";
import Styles from "@modules/account/components/orders/Decision/SendingCheck/SendingCheck.module.scss";
import cn from "classnames";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch, useSelector } from "react-redux";
import Alert from "@modules/account/components/shared/Alert";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { AxiosResponse } from "axios";

interface IProps {
  decision: any;
  onChange: (res: AxiosResponse) => void;
}

const SendingCheck: React.FC<IProps> = (props) => {
  const { decision, onChange } = props;
  const firstAddress = decision.options.addresses[0];
  const secondAddress = decision.options.addresses[1];
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  const initialValues = {
    address: decision.solved === 1 ? decision.options.selectedAddress : null,
  };

  const addressTemplate = (address: string) => {
    //todo: load from server
    const corporation = "S3 Stores, Inc.";

    return (
      <div className={Styles.decisionCardBodyText}>
        <b className={cn(["d-inline-block", Styles.decisionCardBodyTitle])}>
          {corporation}
        </b>
        <br />
        {address}
      </div>
    );
  };

  if (alert) {
    breakpoint({
      xs: function () {
        dispatch(setAlertAction(null));
        dispatch(setMobileAlertAction(alert));
        dispatch(showMobileAlertAction(true));
        dispatch(setVisibleShadowPanelAction(true));
        // router.push("/account/orders/open-orders/decisions-required");
      },
      md: function () {},
    });
  }

  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
          address: values.address,
        },
        success(res: AxiosResponse) {
          onChange(res);
          actions.setSubmitting(false);
        },
      })
    );

    setShow(true);
    dispatch(
      setAlertAction({
        variant: "decisionSuccess",
        message: `Thank you for your payment!
              We are looking forward to doing business with you again.`,
      })
    );
  }

  return (
    <Formik initialValues={initialValues} onSubmit={submit}>
      {({ values, isSubmitting, handleChange }) => {
        return (
          <Form className={cn(Styles.decision)}>
            <h1
              className={cn([Styles.decision__header, Styles.decisionHeader])}
            >
              PO: Sending check
            </h1>
            {alert ? (
              <Alert
                show={show}
                variant={alert.variant}
                message={alert.message}
                classes={{
                  container: "pt-20 pb-5 pt-lg-0",
                  alert: ["account-inner-page_alert"],
                }}
              />
            ) : (
              <>
                <p
                  className={cn([
                    Styles.decision__caption,
                    Styles.decisionCaption,
                  ])}
                >
                  Your Purchase Order was shipped and delivered.
                  <br />
                  Please make a payment according to the invoice attached.
                </p>
                <p className={cn([Styles.decisionCaption, "m-0"])}>
                  Make a check payable to <b>S3 Stores, Inc.</b> and send it to
                </p>
                <CardOr
                  classes={{
                    block: [
                      Styles.decision__cardOrCard,
                      Styles.decisionCardOrCard,
                      Styles.decisionElement,
                    ],
                    card: [Styles.decisionCardBody],
                  }}
                  radioButtons={{
                    valueFirst: 0,
                    valueSecond: 1,
                    name: "address",
                    checkedValue: values.address,
                    disabled: isSubmitting,
                    onChange: handleChange,
                    className: Styles.decisionCardBody,
                  }}
                  cardFirst={addressTemplate(firstAddress)}
                  cardSecond={addressTemplate(secondAddress)}
                />
                <div className={Styles.decisionElement}>
                  <button
                    className={cn([
                      "form-button",
                      "w-100",
                      "w-md-auto",
                      "mx-lg-0",
                      "mx-auto",
                      Styles.button,
                    ])}
                    disabled={values.address === null}
                  >
                    <span className={"d-none d-md-inline"}>
                      I sent check to this address
                    </span>
                    <span className={"d-md-none"}>Check sent</span>
                  </button>
                </div>
              </>
            )}
          </Form>
        );
      }}
    </Formik>
  );
};

export default SendingCheck;
