import React from "react";
import Item from "@client/modules/account/components/orders/Decisions/Item";
import classnames from "classnames";

interface PropsInterface {
  decisions: Record<any, any>[];
  className?: any;
}

const List: React.FC<PropsInterface> = function (props: PropsInterface) {
  const { decisions, className } = props;
  const items = [];

  for (const decision of decisions) {
    items.push(
      <div className="row d-block m-sm-0">
        <Item decision={decision} />
      </div>
    );
  }

  return <div className={classnames(className)}>{items}</div>;
};

export default List;
