import React from "react";
import classnames from "classnames";
import TableRow, {
  RowInterface,
} from "@modules/account/components/orders/Decision/TableRow";

export enum TableTypes {
  inStock = "inStock",
  outOfStock = "outOfStock",
  discontinued = "discontinued",
  licenseRequired = "licenseRequired",
}

interface IProps {
  tableType: TableTypes;
  items: RowInterface[];
}

const Table: React.FC<IProps> = (props: IProps) => {
  const tableHeaders = {
    inStock: {
      itemNameSkuQty: "Item / SKU / Qty in stock",
      qty: "Qty in stock",
      qtyDesktop: "Quantity in stock",
    },
    outOfStock: {
      itemNameSkuQty: "Item / SKU / Qty required",
      qty: "Qty required",
      qtyDesktop: "Quantity required",
    },
    discontinued: {
      itemNameSkuQty: "Item / SKU / Qty discontinued",
      qty: "Qty discontinued",
      qtyDesktop: "Quantity discontinued",
    },
    licenseRequired: {
      itemNameSkuQty: "Item / SKU",
      qty: "Qty",
      qtyDesktop: "Quantity",
    },
  };
  const { tableType, items } = props;
  const classes = {
    hat: ["estimate-table-row", "estimate-table-row_hat"],
  };

  let hatModifier;
  let tableCaption;

  switch (tableType) {
    case TableTypes.inStock:
      hatModifier = "estimate-table-hat_theme_green";
      tableCaption = "The items listed below are currently 'in stock':";
      break;
    case TableTypes.outOfStock:
      hatModifier = "estimate-table-hat_theme_yellow";
      tableCaption =
        "The following items are currently ‘out of stock’\n ETA date(s) are shown below:";
      break;
    case TableTypes.discontinued:
      hatModifier = "estimate-table-hat_theme_red";
      tableCaption =
        "All items you ordered are currently discontinued / 'out of stock' without definite re-stocking date:";
      break;
    case TableTypes.licenseRequired:
      hatModifier = "estimate-table-hat_theme_grey";
      tableCaption = "You have ordered the following items:";
  }

  classes.hat.push(hatModifier);

  function rowsTemplates() {
    return items.map((value: RowInterface) => {
      const { name, sku, amount } = value;
      let date = value.date;

      switch (tableType) {
        case TableTypes.inStock:
        case TableTypes.licenseRequired:
          date = "";
          break;
        case TableTypes.discontinued:
          date = "Unknown";
      }

      return (
        <TableRow
          row={{ name, sku, amount, date }}
          qtyHeader={tableHeaders[tableType].qty}
        />
      );
    });
  }

  function dateColumnTemplate(type: TableTypes) {
    if ([TableTypes.outOfStock, TableTypes.discontinued].includes(type)) {
      return <span>ETA date</span>;
    }
  }

  return (
    <div className="estimate-table decision__estimate-table">
      <p className="estimate-table-caption estimate-table__caption">
        {tableCaption}
      </p>

      <div className={classnames(classes.hat)}>
        <span className={"d-none d-lg-block text-start"}>Item name / SKU</span>
        <span className={"d-lg-none text-start"}>
          {tableHeaders[tableType].itemNameSkuQty}
        </span>

        <span className={"d-none d-lg-block"}>
          {tableHeaders[tableType].qtyDesktop}
        </span>
        <span className={"d-none d-md-block d-lg-none text-center"}>
          {tableHeaders[tableType].qty}
        </span>

        {dateColumnTemplate(tableType)}
      </div>

      {rowsTemplates()}
    </div>
  );
};

export default Table;
