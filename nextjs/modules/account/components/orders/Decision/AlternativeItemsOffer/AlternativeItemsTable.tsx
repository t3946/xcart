import React from "react";
import OrderTable from "@modules/account/components/order/order-table/OrderTable";
import ProductCell from "@modules/account/components/order/order-table/ProductCell";

const getTableConfig = (type: string) => {
  switch (type) {
    case "inStock":
      return {
        theme: "green",
        caption:
          "As an alternative we can offer the following item(s) which are 'in stock':",
      };

    case "outOfStock":
      return {
        theme: "yellow",
        caption:
          "The following item(s) which you have ordered are 'out of stock':",
      };

    default:
      return {
        theme: "yellow",
        caption:
          "The following item(s) which you have ordered are 'out of stock':",
      };
  }
};

interface IProps {
  type: string;
  items: any;
}

const AlternativeItemsTable: React.FC<IProps> = ({ type, items }) => {
  return (
    <OrderTable
      {...{
        ...getTableConfig(type),
        header: [
          <span>Item name / SKU</span>,

          <>
            <span className="d-none d-md-block">Quantity required</span>
          </>,

          <>
            <span>{type === "outOfStock" && "ETA date"}</span>
          </>,
        ],
        items: items,
        classes: {
          table: ["px-md-2", "px-lg-0", "mb-md-4"],
          row: ["flex-wrap", "flex-md-nowrap"],
          columns: [
            "text-start col col-md me-auto",
            "col-md-3",
            "col-3 col-md-3 col-xl-2 text-end",
            "col-12 col-md-auto",
          ],
        },
        rowItemTemplates: (item) => [
          <ProductCell name={item.name} sku={item.sku} url={item.url} />,
          <span className="d-none d-md-block">{item.amount}</span>,
          <span className="d-none d-md-block">{item.date}</span>,
          <div className="d-flex justify-content-between d-md-none mt-1">
            <span>Quantity required: {item.amount}</span>
            <span>{item.date}</span>
          </div>,
        ],
      }}
    />
  );
};

export default AlternativeItemsTable;
