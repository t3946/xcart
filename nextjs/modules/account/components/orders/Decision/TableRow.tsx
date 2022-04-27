import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/Table.module.scss";
import { TableTypes } from "@modules/account/components/orders/Decision/Table";
import Price from "@components/common/price/Price";

export interface RowInterface {
  name: string;
  sku: string;
  amount: number;
  price?: number;
  date?: string;
  total?: number;
  image: string;
}

interface IProps {
  row: RowInterface;
  qtyHeader: string;
  type: TableTypes;
}

const TableRow: React.FC<IProps> = (props: IProps) => {
  const { row, qtyHeader, type } = props;
  const { name, sku, amount, price, date, total, image } = row;
  const isOrder = type === TableTypes.increaseInShippingCharge;

  if (type === TableTypes.increaseInShippingCharge) {
    return (
      <div
        className={cn([
          Styles.estimateTableRow,
          Styles.estimateTableRow_product,
          Styles.estimateTableRow_order_product,
          { [Styles.estimateTableRow_order]: isOrder },
        ])}
      >
        <div
          style={{
            background: `url(${image}) left center / contain no-repeat`,
          }}
          className={cn([
            "d-none",
            "d-lg-block",
            Styles.estimateTableProductImage,
          ])}
        />

        <span
          className={cn([
            "text-left",
            Styles.estimateTableProductNameSku_order,
          ])}
        >
          <span>{name}</span>
          <br />
          <span className={cn([Styles.estimateTableProductSku])}>{sku}</span>
        </span>
        <span className={"d-none d-lg-block"}>
          <Price price={price} />
        </span>
        <span className={"d-none d-md-block"}>
          <span className="d-lg-none">
            <Price price={price} /> x{" "}
          </span>
          {amount}
        </span>

        <span
          className={"d-flex d-md-none text-start mt-2 justify-content-between"}
        >
          <span className={Styles.estimateTableProductPrice}>
            <Price price={price} />
          </span>
          <span className={Styles.estimateTableProductAmount}>x {amount}</span>
          <span className="text-right">
            <Price price={total} />
          </span>
        </span>
        <span className="d-none d-md-block text-right">
          <Price price={total} />
        </span>
      </div>
    );
  }

  return (
    <div className="estimate-table-row estimate-table-row_product">
      <span>
        <span className={"estimate-table-product-name"}>{name}</span>
        <br />
        <span className={"estimate-table-product-sku"}>{sku}</span>
      </span>

      <span className={"d-none d-md-block"}>{amount}</span>
      <span
        className={"d-flex d-md-none text-start mt-2 justify-content-between"}
      >
        <span>
          {qtyHeader}: {amount}
        </span>
        <span>{date}</span>
      </span>

      <span className={"d-none d-md-block"}>{date}</span>
    </div>
  );
};

export default TableRow;
