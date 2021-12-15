import React from "react";
import cn from "classnames";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/router";
import Alert from "@modules/account/components/shared/Alert";
import StoreInterface from "@modules/account/ts/types/store.type";
import { sentAchTransferAction } from "@redux/actions/account-actions/DecisionsActions";
import { setAlertAction } from "@redux/actions/account-actions/ProfileActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import GreyGrid from "@modules/ui/GreyGrid";
import Styles from "@modules/account/components/orders/Decision/AchPaymentIsRequired/AchPaymentIsRequired.module.scss";

const AchPaymentIsRequired: React.FC = () => {
  const mockData = {
    bank: {
      name: "Evolve Bank & Trust",
      address: ` 6070 Poplar Ave 
      Suite 200 
    Memphis, TN 38119`,
      swiftCode: "FRNAUS44XXX",
    },
    company: {
      name: "S3 Stores Inc",
      "Routing / ABA Number": "084106768",
      accountNumber: "9800775190",
      companyAddress: "270 Trace Colony Park, Suite B, Ridgeland, MS 39157",
    },
  };
  const transferData = (mockData) => {
    const itemList: React.ReactNode[] = [];
    itemList.push(
      <div className={Styles.gridLineItem}>
        <div className={Styles.gridLineItemName}>Bank Name:</div>
        <div className={Styles.gridLineItemValue}>{mockData.bank.name}</div>
        <div className={Styles.gridLineItemName}>Bank Address:</div>
        <div className={Styles.gridLineItemValue}>{mockData.bank.address}</div>
        <div className={Styles.gridLineItemName}>Bank SWIFT Code:</div>
        <div className={Styles.gridLineItemValue}>
          {mockData.bank.swiftCode}
        </div>
      </div>
    );
    itemList.push(
      <div className={Styles.gridLineItem}>
        <div className={cn(Styles.gridLineItemName, "mb-6", "mb-md-0")}>
          Company / <br className="d-md-none" />
          Account Name:
        </div>

        <div className={Styles.gridLineItemValue}>{mockData.company.name}</div>
        <div className={cn(Styles.gridLineItemName, "mb-2", "mb-md-0")}>
          Routing / <br className="d-md-none" />
          ABA Number:
        </div>

        <div className={Styles.gridLineItemValue}>
          {mockData.company["Routing / ABA Number"]}
        </div>

        <div className={cn(Styles.gridLineItemName, "mb-20", "mb-lg-3")}>
          Account Number:
        </div>

        <div className={cn(Styles.gridLineItemValue, "mb-20", "mb-lg-3")}>
          {mockData.company.accountNumber}
        </div>

        <div className={Styles.gridLineItemName}>Company Address:</div>
        <div className={Styles.gridLineItemValue}>
          {mockData.company.companyAddress}
        </div>
      </div>
    );

    return itemList;
  };
  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  }, []);
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();
  const [disabled, setDisabled] = React.useState<boolean>(false);
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

  const iSentAchTransferHandler = () => {
    setDisabled(true);
    dispatch(
      sentAchTransferAction({
        data: {},
        success(res) {
          setShow(true);
          dispatch(
            setAlertAction({
              variant: "decisionSuccess",
              message: `Upon confirming the receipt of the funds we'll ship your order.
              Thank you for your business!`,
            })
          );
        },
      })
    );

    setShow(true);
    dispatch(
      setAlertAction({
        variant: "decisionSuccess",
        message: `Upon confirming the receipt of the funds we'll ship your order.
              Thank you for your business!`,
      })
    );
    //
  };

  return (
    <>
      {alert ? (
        <>
          <InnerPage
            hatClasses={Styles.decisionHeader}
            headerClasses={Styles.decisionHeaderText}
            header={"ACH payment is required due to high risk"}
            bodyClasses={cn(Styles.decisionContent, "p-0")}
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
          hatClasses={Styles.decisionHeader}
          headerClasses={Styles.decisionHeaderText}
          header={"ACH payment is required due to high risk"}
          bodyClasses={cn(Styles.decisionContent, "p-0")}
          footerClasses={Styles.decisionFooter}
          footer={
            <>
              <p
                className={cn(
                  Styles.decisionFooter__text,
                  Styles.decisionFooterText
                )}
              >
                Upon sending the funds, please click
              </p>

              <div className={cn("ps-3", "ps-lg-0")}>
                <button
                  className={cn(
                    "form-button w-md-auto",
                    "mx-md-auto",
                    "mx-lg-0",
                    Styles.button
                  )}
                  onClick={iSentAchTransferHandler}
                  disabled={disabled}
                >
                  i sent ach transfer
                </button>
              </div>
            </>
          }
        >
          <p
            className={cn(
              Styles.decisionText,
              Styles.decision__text,
              "mb-20",
              "mb-md-4",
              "mb-lg-12"
            )}
          >
            Due to high risk associated with your order, please remit your
            payment via ACH transfer.
          </p>

          <p className={cn(Styles.decisionText, Styles.decision__text)}>
            To pay us via ACH transfer, please send funds to our Evolve Bank &
            Trust USD checking account:
          </p>

          <GreyGrid
            classes={{
              item: [Styles.gridLine, "my-0"],
              block: [Styles.decision__grid, Styles.decisionGrid, "m-lg-0"],
            }}
            items={transferData(mockData)}
          />
        </InnerPage>
      )}
    </>
  );
};

export default AchPaymentIsRequired;
