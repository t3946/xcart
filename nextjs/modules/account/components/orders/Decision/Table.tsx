import React from "react";
import classnames from "classnames";
import TableRow, {
  RowInterface,
} from "@modules/account/components/orders/Decision/TableRow";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/Table.module.scss";

export enum TableTypes {
  inStock = "inStock",
  outOfStock = "outOfStock",
  discontinued = "discontinued",
  licenseRequired = "licenseRequired",
  increaseInShippingCharge = "increaseInShippingCharge",
  alternativeItemsOfferOutOfStock = "alternativeItemsOfferOutOfStock",
  alternativeItemsOfferInStock = "alternativeItemsOfferInStock",
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
    increaseInShippingCharge: {
      itemNameSkuQty: "Item name / SKU",
      qty: "Price x Qty",
      qtyDesktop: "Qty ordered",
    },
    alternativeItemsOfferOutOfStock: {
      itemNameSkuQty: "Item name / SKU",
      qty: "Price x Qty",
      qtyDesktop: "Qty ordered",
    },
    alternativeItemsOfferInStock: {
      itemNameSkuQty: "Item name / SKU",
      qty: "Price x Qty",
      qtyDesktop: "Qty ordered",
    },
  };
  const { tableType, items } = props;
  const classes = {
    hat: [
      Styles.estimateTableRow,
      Styles.estimateTableRow_hat,
      {
        [Styles.estimateTableRow_order]:
          tableType === TableTypes.increaseInShippingCharge,
      },
    ],
  };

  let hatModifier;
  let tableCaption;

  switch (tableType) {
    case TableTypes.inStock:
      hatModifier = Styles.estimateTableHat_theme_green;
      tableCaption = "The items listed below are currently 'in stock':";
      break;
    case TableTypes.outOfStock:
      hatModifier = Styles.estimateTableHat_theme_yellow;
      tableCaption =
        "The following items are currently ‘out of stock’\n ETA date(s) are shown below:";
      break;
    case TableTypes.discontinued:
      hatModifier = Styles.estimateTableHat_theme_red;
      tableCaption =
        "All items you ordered are currently discontinued / 'out of stock' without definite re-stocking date:";
      break;

    case TableTypes.licenseRequired:
      hatModifier = Styles.estimateTableHat_theme_grey;
      tableCaption = "You have ordered the following items:";
      break;

    case TableTypes.increaseInShippingCharge:
      hatModifier = Styles.estimateTableHat_theme_grey;

    case TableTypes.licenseRequired:
      hatModifier = Styles.estimateTableHat_theme_grey;
      tableCaption = "You have ordered the following items:";
      break;

    case TableTypes.alternativeItemsOfferOutOfStock:
      hatModifier = Styles.estimateTableHat_theme_yellow;
      tableCaption =
        "The following item(s) which you have ordered are 'out of stock':";
      break;

    case TableTypes.alternativeItemsOfferInStock:
      hatModifier = Styles.estimateTableHat_theme_green;
      tableCaption =
        "As an alternative we can offer the following item(s) which are 'in stock':";
      break;
  }

  classes.hat.push(hatModifier);

  function rowsTemplates() {
    return items.map((value: RowInterface, index) => {
      const { name, sku, amount, price, total, image } = value;
      let date = value.date;

      switch (tableType) {
        case TableTypes.inStock:
        case TableTypes.licenseRequired:
        case TableTypes.alternativeItemsOfferInStock:
          date = "";
          break;
        case TableTypes.discontinued:
          date = "Unknown";
          break;
        case TableTypes.increaseInShippingCharge:
          date = null;
      }

      return (
        <TableRow
          row={{ name, sku, amount, date, price, total, image }}
          qtyHeader={tableHeaders[tableType].qty}
          type={tableType}
          key={index}
        />
      );
    });
  }

  function dateColumnTemplate(type: TableTypes) {
    if (
      [
        TableTypes.outOfStock,
        TableTypes.discontinued,
        TableTypes.alternativeItemsOfferOutOfStock,
      ].includes(type)
    ) {
      return <span>ETA date</span>;
    }
  }

  function priceColumnTemplate(type: TableTypes) {
    if (type === TableTypes.increaseInShippingCharge) {
      return <span className={"d-none d-lg-block"}>Price</span>;
    }
  }

  function extendedColumnTemplate(type: TableTypes) {
    if (type === TableTypes.increaseInShippingCharge) {
      return <span>Extended</span>;
    }
  }

  return (
    <div
      className={cn([
        Styles.estimateTable,
        "decision__estimate-table",
        {
          [Styles.decision__table_increaseInShippingCharge]:
            tableType === TableTypes.increaseInShippingCharge,
        },
      ])}
    >
      {tableCaption && (
        <p
          className={cn([
            Styles.estimateTableCaption,
            Styles.estimateTable__caption,
          ])}
        >
          {tableCaption}
        </p>
      )}

      <div className={classnames(classes.hat)}>
        {tableType === TableTypes.increaseInShippingCharge && (
          <span className="d-none d-lg-block" />
        )}
        <span className={cn(["d-none", "d-lg-block", "text-start"])}>
          Item name / SKU
        </span>
        <span
          className={cn([
            "d-lg-none",
            "text-start",
            {
              "text-md-center":
                tableType === TableTypes.increaseInShippingCharge,
            },
          ])}
        >
          {tableHeaders[tableType].itemNameSkuQty}
        </span>

        {priceColumnTemplate(tableType)}

        <span className={"d-none d-lg-block"}>
          {tableHeaders[tableType].qtyDesktop}
        </span>

        <span className={"d-none d-md-block d-lg-none text-center"}>
          {tableHeaders[tableType].qty}
        </span>

        {dateColumnTemplate(tableType)}

        {extendedColumnTemplate(tableType)}
      </div>

      {rowsTemplates()}
    </div>
  );
};

export default Table;
