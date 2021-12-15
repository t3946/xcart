import React from "react";
import CardOr from "@modules/ui/CardOr";
import { Formik, Form } from "formik";
import Styles from "@modules/account/components/orders/Decision/SendingCheck/SendingCheck.module.scss";
import cn from "classnames";
import { checkSentAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch, useSelector } from "react-redux";
import Alert from "@modules/account/components/shared/Alert";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

interface IAddress {
  name: string;
  address: string;
}

interface IProps {
  firstAddress: IAddress;
  secondAddress: IAddress;
}

const SendingCheck: React.FC<IProps> = ({ firstAddress, secondAddress }) => {
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();
  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  const initialValues = {
    address: null,
  };

  const addressTemplate = (address: IAddress) => {
    return (
      <div className={Styles.decisionCardBodyText}>
        <b className={cn(["d-inline-block", Styles.decisionCardBodyTitle])}>
          {address.name}
        </b>
        <br />
        {address.address}
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

  const submit = (values) => {
    dispatch(
      checkSentAction({
        data: { address: values.address },
        success(res) {
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Thank you for your payment!
              We are looking forward to doing business with you again.`,
            })
          );
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
    //
  };

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
                    valueFirst: "USA",
                    valueSecond: "Canada",
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
