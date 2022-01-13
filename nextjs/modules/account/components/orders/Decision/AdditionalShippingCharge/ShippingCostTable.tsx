import React from "react";
import GreyGrid from "@modules/ui/GreyGrid";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/AdditionalShippingCharge/ShippingCostTable.module.scss";

const columnPadding = ["px-2", "px-md-3", "px-lg-4"];
const classes = {
  columnPadding,
  shippingGrid: [
    "d-flex",
    "justify-content-between",
    "align-items-center",
    Styles.gridItemSublinesItem,
  ],
};

interface IProps {
  actualCost: number;
  costPaid: number;
  shippingCharge: number;
  className?: any;
}

const ShippingCostTable: React.FC<IProps> = ({
  actualCost,
  costPaid,
  shippingCharge,
  className,
}) => {
  return (
    <GreyGrid
      items={[
        <>
          <div className={cn(classes.shippingGrid)}>
            <div>Actual shipping cost</div> <div>$ {actualCost.toFixed(2)}</div>
          </div>
          <div className={cn(classes.shippingGrid)}>
            <div>Shipping cost paid</div> <div>$ {costPaid.toFixed(2)}</div>
          </div>
        </>,
        <div
          className={cn(
            "d-flex",
            "justify-content-between",
            "align-items-center",
            "fw-bold"
          )}
        >
          <div>Additional shipping charge</div>{" "}
          <div>$ {shippingCharge.toFixed(2)}</div>
        </div>,
      ]}
      classes={{
        item: [
          Styles.gridItem,
          Styles.gridItem_border_none,
          "m-0",
          classes.columnPadding,
        ],
        block: [Styles.gridShipping, className],
      }}
    />
  );
};

export default ShippingCostTable;
