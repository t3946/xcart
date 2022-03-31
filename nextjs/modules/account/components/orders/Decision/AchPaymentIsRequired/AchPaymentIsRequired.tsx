import React from "react";
import cn from "classnames";
import {useDispatch} from "react-redux";
import {solveDecisionAction} from "@redux/actions/account-actions/DecisionsActions";
import InnerPage from "@components/common/inner-page/InnerPage";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import Styles from "@modules/account/components/orders/Decision/AchPaymentIsRequired/AchPaymentIsRequired.module.scss";
import {AxiosResponse} from "axios";
import DecisionsInterface from "@modules/account/ts/types/decision";
import Button from "@modules/ui/forms/Button";
import useSnackbar from "@modules/account/hooks/useSnackbar";
import AddressText from "@components/common/address-text/AddressText";

interface IProps {
  onChange: (res: AxiosResponse) => any;
  decision: DecisionsInterface;
}

const AchPaymentIsRequired: React.FC<IProps> = (props) => {
  const { onChange, decision } = props;
  const transferData = (transfer) => {
    const itemList: React.ReactNode[] = [];
    itemList.push(
      <div className={Styles.gridLineItem}>
        <div className={Styles.gridLineItemName}>Bank Name:</div>
        <div className={Styles.gridLineItemValue}>{transfer.bank.name}</div>
        <div className={Styles.gridLineItemName}>Bank Address:</div>
        <div className={Styles.gridLineItemValue}>
          <AddressText address={transfer.bank.address} />
        </div>
        <div className={Styles.gridLineItemName}>Bank SWIFT Code:</div>
        <div className={Styles.gridLineItemValue}>
          {transfer.bank.swiftCode}
        </div>
      </div>
    );
    itemList.push(
      <div className={Styles.gridLineItem}>
        <div className={cn(Styles.gridLineItemName, "mb-6", "mb-md-0")}>
          Company / <br className="d-md-none" />
          Account Name:
        </div>

        <div className={Styles.gridLineItemValue}>{transfer.company.name}</div>
        <div className={cn(Styles.gridLineItemName, "mb-2", "mb-md-0")}>
          Routing / <br className="d-md-none" />
          ABA Number:
        </div>

        <div className={Styles.gridLineItemValue}>
          {transfer.company.routingNumber}
        </div>

        <div className={cn(Styles.gridLineItemName, "mb-20", "mb-lg-3")}>
          Account Number:
        </div>

        <div className={cn(Styles.gridLineItemValue, "mb-20", "mb-lg-3")}>
          {transfer.company.accountNumber}
        </div>

        <div className={Styles.gridLineItemName}>Company Address:</div>
        <div className={Styles.gridLineItemValue}>
          {transfer.company.companyAddress}
        </div>
      </div>
    );

    return itemList;
  };

  const dispatch = useDispatch();
  const [disabled, setDisabled] = React.useState<boolean>(false);
  const snack = useSnackbar();

  function submit() {
    setDisabled(true);
    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
        },
        success(res) {
          setDisabled(false);
          onChange(res);
          snack.show(`Upon confirming the receipt of the funds we'll ship your order.
              Thank you for your business`);
        },
      })
    );
  }

  return (
    <InnerPage
      hatClasses={Styles.decisionHeader}
      headerClasses={Styles.decisionHeaderText}
      header={"ACH payment is required due to high risk"}
      bodyClasses={cn(Styles.decisionContent, "p-0")}
      footerClasses={[Styles.decisionFooter, { "d-none": !!decision.solved }]}
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
            <Button
              className={cn(
                "w-md-auto",
                "mx-md-auto",
                "mx-lg-0",
                Styles.button,
                { "d-none": !!decision.solved }
              )}
              onClick={submit}
              disabled={disabled || !!decision.solved}
            >
              i sent ach transfer
            </Button>
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
        Due to high risk associated with your order, please remit your payment
        via ACH transfer.
      </p>

      <p className={cn(Styles.decisionText, Styles.decision__text)}>
        To pay us via ACH transfer, please send funds to our Evolve Bank & Trust
        USD checking account:
      </p>

      <GreyGrid
        classes={{
          item: [Styles.gridLine, "my-0"],
          list: [Styles.decision__grid, Styles.decisionGrid, "m-lg-0"],
        }}
        items={transferData(decision.options)}
      />
    </InnerPage>
  );
};

export default AchPaymentIsRequired;
