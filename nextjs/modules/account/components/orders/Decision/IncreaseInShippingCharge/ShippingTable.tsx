import React from "react";
import ProductImage from "@modules/account/components/order/order-table/ProductImage";
import ProductCell from "@modules/account/components/order/order-table/ProductCell";
import OrderTable from "@modules/account/components/order/order-table/OrderTable";
import TableFooter from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/TableFooter";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";

interface IProps {
  group: any;
  showCaption?: boolean;
}

const ShippingTable: React.FC<IProps> = ({ group, showCaption }) => {
  const items = group.products.map((item) => {
    const total = parseFloat((item.price * item.amount).toFixed(2));
    return { ...item, total };
  });

  return (
    <>
      <div className={cn([Styles.table__name, Styles.tableName])}>
        The items below are shipped from {group.manufacturer.city},{" "}
        {group.manufacturer.state}, {group.manufacturer.country}
      </div>
      <OrderTable
        theme="grey"
        caption={
          showCaption ? "You have ordered the following items:" : undefined
        }
        header={[
          null,
          <span>Item name / SKU</span>,
          <>
            <span className="d-none d-lg-block">Price</span>
          </>,

          <>
            <span className="d-none d-lg-block">Qty ordered</span>
            <span className="d-none d-md-block d-lg-none">Price x Qty</span>
          </>,

          <span>Extended</span>,
        ]}
        items={items}
        classes={{
          table: ["px-md-2", "px-lg-0", "mb-md-4"],
          row: ["flex-wrap", "flex-md-nowrap"],
          columns: [
            "col-lg-1",
            "text-start col-9 col-md me-auto",
            "col-lg-2",
            "col-md-2",
            "col-md-2 col-lg-1",
            "col-4 col-md-auto text-start",
            "col-4 col-md-auto text-start",
            "col-4 col-md-auto",
          ],
        }}
        rowItemTemplates={(item) => [
          <ProductImage image={item.image} />,
          <ProductCell name={item.product} sku={item.code} />,
          <span className="d-none d-lg-block">US$ {item.price}</span>,
          <>
            <span className="d-none d-lg-block">{item.amount}</span>
            <span className="d-none d-md-block d-lg-none">
              US$ {item.price} x {item.amount}
            </span>
          </>,
          <span className="d-none d-md-block text-end">
            US$ {(item.amount * item.price).toFixed(2)}
          </span>,
          <span className="d-md-none">US$ {item.price}</span>,
          <span className="d-md-none">x {item.amount}</span>,
          <span className="d-md-none">
            US$ {(item.amount * item.price).toFixed(2)}
          </span>,
        ]}
      />

      <TableFooter
        paymentStatus={group.a2bStatus}
        shippingStatus={group.a2cStatus}
        regularShipping={group.shippingGross}
        salesTax={group.totalPst}
        vatTax={group.totalTax}
        subtotal={group.totalGross}
      />
    </>
  );
};

export default ShippingTable;
