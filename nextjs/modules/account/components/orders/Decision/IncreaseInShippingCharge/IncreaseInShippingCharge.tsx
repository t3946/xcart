import React from "react";
import DecisionsInterface from "@modules/account/ts/types/decision";
import OrderTable from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/OrderTable";
import cn from "classnames";
import Styles
  from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";
import {solveDecisionAction} from "@redux/actions/account-actions/DecisionsActions";
import Alert from "@modules/account/components/shared/Alert";
import {setAlertAction} from "@redux/actions/account-actions/ProfileActions";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import {setVisibleShadowPanelAction} from "@redux/actions/account-actions/ShadowPanelActions";
import {useDispatch, useSelector} from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import {useRouter} from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import Button, {ETheme} from "@modules/ui/forms/Button";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const IncreaseInShippingCharge: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
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
        router.push("/account/orders/open-orders/decisions-required");
      },
      md: function () {},
    });
  }

  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);

  const approveHandlerClick = (e: React.MouseEvent<HTMLButtonElement>) => {
    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
          choice: "approve",
        },
        success(res) {
          setShow(true);

          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Thank you for your approval!
              The order will be shipped to you shortly.`,
            })
          );
        },
      })
    );
  };

  const cancelOrderHandlerClick = (e: React.MouseEvent<HTMLButtonElement>) => {
    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
          choice: "cancel",
        },
        success(res) {
          setShow(true);

          dispatch(
            setAlertAction({
              variant: "decisionWarning",
              message: `Your order has been canceled.
              Again, we apologize for the inconvenience.`,
            })
          );
        },
      })
    );
  };

  return (
    <div className={Styles.decision}>
      <h1 className={cn([Styles.decisionHeader, Styles.decision__header])}>
        PO: Increase in shipping charge
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
              "d-none",
              "d-lg-block",
              Styles.decision__text,
              Styles.decisionText,
            ])}
          >
            Shipping charge was adjusted on the PO you've submitted to us:
          </p>
          <p
            className={cn([
              "d-lg-none",
              Styles.decision__text,
              Styles.decisionText,
            ])}
          >
            Total Shipping Cost was adjusted on the PO you've submitted to us:
          </p>
          <OrderTable order={decision.order} />
          <p
            className={cn([
              Styles.decisionText,
              Styles.decisionFooterText,
              Styles.decision__footerText,
            ])}
          >
            Could you please approve this adjustment or cancel order.
          </p>
          <div
            className={cn([
              "d-flex",
              "flex-dir-column",
              Styles.decisionFooterButtons,
              Styles.decisionText,
            ])}
          >
            <Button
              className={cn([
                "w-auto",
                Styles.button,
                Styles.decisionFooterButtonsApprove,
              ])}
              disabled={decision.solved === 1}
              onClick={approveHandlerClick}
            >
              <span className="d-none d-md-block">
                Approve increase in shipping charge
              </span>
              <span className="d-md-none">Approve</span>
            </Button>
            <Button
              className={cn([
                "w-auto",
                Styles.button,
                Styles.decisionFooterButtonsCancel,
              ])}
              onClick={cancelOrderHandlerClick}
              disabled={decision.solved === 1}
              theme={ETheme.outlined}
            >
              Cancel order
            </Button>
          </div>
        </>
      )}
    </div>
  );
};

export default IncreaseInShippingCharge;
