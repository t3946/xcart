import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/OriginalPurchaseOrder.module.scss";
import AsFile from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/AsFile";
import Card from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/Card";
import { useDispatch } from "react-redux";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Button from "@modules/ui/forms/Button";

interface IProps {
  onChange: (message: string) => any;
  decision: DecisionsInterface;
}

const OriginalPurchaseOrder: React.FC<IProps> = (props: IProps) => {
  const { onChange, decision } = props;
  console.log({ decision });
  const classes = {
    p: [
      Styles.decision__caption,
      Styles.decisionCaption,
      "estimate-table-caption",
      "estimate-table__caption",
    ],
  };
  const dispatch = useDispatch();
  const [submitting, setSubmitting] = React.useState(false);
  const fax_number = useSelectorAccount((e) => e.config.site.fax_number);

  function submit() {
    setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
          method: "fax",
        },
        success() {
          setSubmitting(false);
        },
      })
    );

    onChange("Purchase Order has sent");
  }

  function cardOrCard() {
    const cardFile = (
      <Card>
        <AsFile decision={decision} onChange={onChange} />
      </Card>
    );
    const or = (
      <div
        className={cn([
          "d-flex",
          "align-items-center",
          "justify-content-center",
          "text-uppercase",
          Styles.decisionCardLayout__or,
          Styles.or,
        ])}
      >
        or
      </div>
    );
    const cardFax = (
      <Card>
        <div
          className={cn([
            Styles.decisionCardBodyFax,
            "d-flex",
            "flex-dir-column",
            "justify-content-between",
          ])}
        >
          <div className={cn([Styles.cardText])}>
            <b>Via fax to</b>
            <br />
            <b className="text-capitalize">Fax</b> <span>{fax_number}</span>
          </div>
          <Button
            className={cn([
              "form-button",
              "w-lg-auto",
              Styles.button,
              { "d-none": !!decision.solved },
            ])}
            type="button"
            onClick={submit}
            disabled={submitting}
          >
            <span className="d-none d-lg-inline">
              I sent original PO via fax
            </span>
            <span className="d-lg-none">Fax sent</span>
          </Button>
        </div>
      </Card>
    );

    if (decision.solved) {
      if (decision.options.method === "file") {
        return cardFile;
      } else {
        return cardFax;
      }
    }

    return [cardFile, or, cardFax];
  }

  return (
    <>
      <h1
        className={cn(
          Styles.decisionTitle,
          "decision-inner-header",
          "decision__inner-header"
        )}
      >
        Send us original PO
      </h1>

      <p className={cn([classes.p, "mb-20", "mb-lg-4"])}>
        Thank you for submitting your purchase order online!
      </p>

      <p className={cn(classes.p)}>
        However we also require original Purchase Order (PO) sent to us
      </p>

      <div
        className={cn([
          "align-items-center",
          "mx-lg-4",
          Styles.decision__CardLayout,
          Styles.decisionCardLayout,
        ])}
      >
        {cardOrCard()}
      </div>

      <p className={cn([classes.p, "mb-5"])}>
        PS. Please also advise your accounting department to make out a check
        payable to <b>S3 Stores Inc.</b> upon delivery of your order.
      </p>
    </>
  );
};

export default OriginalPurchaseOrder;
