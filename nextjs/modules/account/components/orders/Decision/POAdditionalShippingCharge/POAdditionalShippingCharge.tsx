import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import ShippingCostTable from "@modules/account/components/orders/Decision/AdditionalShippingCharge/ShippingCostTable";
import cn from "classnames";
import Alert from "@modules/account/components/shared/Alert";
import { useRouter } from "next/router";
import StoreInterface from "@modules/account/ts/types/store.type";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { useDispatch, useSelector } from "react-redux";
import { cancelOrderAction } from "@redux/actions/account-actions/DecisionsActions";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

import Styles from "@modules/account/components/orders/Decision/POAdditionalShippingCharge/POAdditionalShippingCharge.module.scss";

const columnPadding = ["px-2", "px-md-3", "px-lg-4"];
const classes = {
  columnPadding,
  text: [columnPadding, Styles.text],
  button: ["form-button", "w-auto", "fw-bold", Styles.button],
};

interface IProps {
  decision: DecisionsInterface;
  onChange: Function;
}

const POAdditionalShippingCharge: React.FC = ({ decision, onChange }) => {
  const dispatch = useDispatch();
  const [submitting, setSubmiting] = React.useState<boolean>(false);
  const breakpoint = useBreakpoint();
  const router = useRouter();
  const alert = useSelector((e: StoreInterface) => e.publicProfile.alert);
  const [show, setShow] = React.useState(alert !== null);
  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);

  if (alert) {
    breakpoint({
      xs: function () {
        dispatch(setAlertAction(null));
        dispatch(setMobileAlertAction(alert));
        dispatch(showMobileAlertAction(true));
        dispatch(setVisibleShadowPanelAction(true));
        router.push("/account/orders/open-orders/decisions-required");
      },
      md: function () {},
    });
  }

  const onCancelOrderHandler = () => {
    setSubmiting(true);
    dispatch(
      cancelOrderAction({
        data: { decision: decision },
        success(res) {
          setSubmiting(false);
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionWarning",
              message: `Your order has now been canceled.
              Again, we apologize for the inconvenience.`,
            })
          );
        },
      })
    );

    setShow(true);
    dispatch(
      setAlertAction({
        variant: "decisionWarning",
        message: `Your order has now been canceled.
              Again, we apologize for the inconvenience.`,
      })
    );
    //
  };
  return (
    <>
      {alert ? (
        <>
          <InnerPage
            hatClasses={Styles.hat}
            header="PO: Additional shipping charge"
            bodyClasses={"p-0"}
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
        </>
      ) : (
        <InnerPage
          hatClasses={Styles.hat}
          header="PO: Additional shipping charge"
          bodyClasses={"p-0"}
        >
          <p className={cn(classes.text, "mb-4")}>
            While reviewing your order, we have found that the shipping estimate
            which you have provided to us (or our shipping quote server gave us)
            was mistaken. This sometimes occurs when the product is oversized or
            somehow irregular in shape or weight. We apologize for any
            inconvenience.
          </p>
          <p className={cn(classes.text, "mb-lg-4")}>
            We have manually recalculated the shipping cost for this order, and
            the actual shipping cost works out to{" "}
            <span className="d-inline-block">{`{{required}}.`}</span> The
            difference that needs to be authorized to process the order is{" "}
            <span className="d-inline-block">{`{{additional}}.`}</span>
          </p>
          <ShippingCostTable
            className={"mb-18 mb-lg-5"}
            actualCost={120}
            costPaid={50}
            shippingCharge={70}
          />
          <p className={cn(classes.text, "mb-20", "mb-lg-4")}>
            Please confirm that you are in agreement with this increase so that
            we can process your purchase order.
          </p>
          <div
            className={cn(
              Styles.buttonLayout,
              classes.columnPadding,
              "d-flex",
              "justify-content-center",
              "justify-content-lg-start"
            )}
          >
            <button disabled={submitting} className={cn(classes.button)}>
              <span className="d-none d-xxl-inline">
                i authorize increase in shipping charge
              </span>
              <span className="d-xxl-none">agree</span>
            </button>
            <button
              disabled={submitting}
              onClick={onCancelOrderHandler}
              className={cn(classes.button, "form-button__outline")}
            >
              Cancel order
            </button>
          </div>
        </InnerPage>
      )}
    </>
  );
};

export default POAdditionalShippingCharge;
