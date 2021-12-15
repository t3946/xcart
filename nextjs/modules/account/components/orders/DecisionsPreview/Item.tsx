import React from "react";
import AlertCheck from "@modules/icon/components/account/check/AlertCheck";
import SandClock from "@modules/icon/components/account/sand-clock/SandClock";
import cn from "classnames";
import Styles from "@modules/account/components/orders/DecisionsPreview/Item.module.scss";

interface IProps {
  decision: Record<any, any>;
  classes?: {
    container: any;
  };
}

const Item = React.forwardRef(function (props: IProps, ref: any) {
  const { decision } = props;
  const decisionName = ["ETA"][decision.type];
  const classes = {
    container: [
      "decisions-item",
      "d-flex",
      "align-items-center",
      "justify-content-between",
      {
        "decisions-item_made": decision.solved,
        "decisions-item_required": !decision.solved,
      },
      props.classes?.container,
    ],
    status: {
      container: [
        "d-flex",
        "align-items-center",
        "decisions-item-status",
        {
          "decisions-item-status_made": decision.solved,
          "decisions-item-status_required": !decision.solved,
        },
      ],
      text: ["d-none", "d-md-inline", "ms-10", "ms-lg-2", "lh-1"],
    },
  };

  function iconTemplate() {
    if (decision.solved) {
      return <AlertCheck className={"decisions-item-icon_made"} />;
    } else {
      return <SandClock className={"decisions-item-icon_required"} />;
    }
  }

  function statusTemplate() {
    const text = decision.solved ? "Decision made" : "Decision required";

    return (
      <span className={cn(classes.status.container)}>
        {iconTemplate()}
        <span className={cn(classes.status.text)}>{text}</span>
      </span>
    );
  }

  return (
    <div className={cn(classes.container)} ref={ref}>
      <span className={"decisions-item-text"}>
        <span className="d-block d-md-inline">{decision.order_number}:</span>{" "}
        <span className="d-block d-md-inline">
          {decisionName} Decision Required
        </span>
      </span>

      <span className="decisions-item-icon">{statusTemplate()}</span>
    </div>
  );
});

export default Item;
