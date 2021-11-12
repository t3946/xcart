import React from "react";
import AlertCheck from "@client/modules/icon/components/account/check/AlertCheck";
import SandClock from "@client/modules/icon/components/account/sand-clock/SandClock";
import classnames from "classnames";

interface PropsInterface {
  decision: Record<any, any>;
}

const Item: React.FC<PropsInterface> = function (props: PropsInterface) {
  const { decision } = props;
  const { order_number } = decision.options;
  const decisionName = ["ETA"][decision.type];

  function iconTemplate() {
    if (decision.resolved) {
      return <AlertCheck className={"decisions-item-icon_made"} />;
    } else {
      return <SandClock className={"decisions-item-icon_required"} />;
    }
  }

  function statusTemplate() {
    const text = decision.resolved ? "Decision made" : "Decision required";
    const classes = [
      "d-flex",
      "align-items-center",
      "decisions-item-status",
      {
        "decisions-item-status_made": decision.resolved,
        "decisions-item-status_required": !decision.resolved,
      },
    ];

    return (
      <span className={classnames(classes)}>
        {iconTemplate()}
        <span className={"d-none d-md-inline decisions-item__status-text"}>
          {text}
        </span>
      </span>
    );
  }

  const classes = {
    item: [
      "decisions-item",
      "d-flex",
      "align-items-center",
      "justify-content-between",
      {
        "decisions-item_made": decision.resolved,
        "decisions-item_required": !decision.resolved,
      },
    ],
  };

  return (
    <div className={classnames(classes.item)}>
      <span className={"decisions-item-text"}>
        <span className="d-block d-md-inline">{order_number}:</span>{" "}
        <span className="d-block d-md-inline">
          {decisionName} Decision Required
        </span>
      </span>

      <span className="decisions-item-icon">{statusTemplate()}</span>
    </div>
  );
};

export default Item;
