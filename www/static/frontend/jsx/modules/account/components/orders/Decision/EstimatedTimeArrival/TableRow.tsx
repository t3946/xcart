import React from "react";

export interface RowInterface {
  name: string;
  sku: string;
  amount: number;
  date?: string;
}

interface IProps {
  row: RowInterface;
  qtyHeader: string;
}

const TableRow: React.FC<IProps> = (props: IProps) => {
  const { row, qtyHeader } = props;
  const { name, sku, amount, date } = row;

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
