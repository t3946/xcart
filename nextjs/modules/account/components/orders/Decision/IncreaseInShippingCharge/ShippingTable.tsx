import React from "react";
import ProductImage from "@modules/account/components/order/order-table/ProductImage";
import ProductCell from "@modules/account/components/order/order-table/ProductCell";
import OrderTable from "@modules/account/components/order/order-table/OrderTable";
import TableFooter from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/TableFooter";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";
import Button, { ETheme } from "@modules/ui/forms/Button";
import Link from "next/link";

interface IProps {
  group: any;
  order: any;
  showCaption?: boolean;
}

const ShippingTable: React.FC<IProps> = (props) => {
  const { group, showCaption, order } = props;

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
          <ProductCell name={item.product} sku={item.code} url={item.url} />,
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
        rowFooterTemplate={(item, index) => {
          if (order.cb_status !== "P" || order.dc_status !== "Z") {
            return null;
          }

          const elements = [];
          let deliveredStatus;

          for (const statusesHistoryElement of group.statuses_history) {
            if (statusesHistoryElement.status === "Z") {
              deliveredStatus = statusesHistoryElement;
            }
          }

          if (deliveredStatus) {
            const timeOneDay = 1000 * 60 * 60 * 24;
            const timeDelivered = new Date(deliveredStatus.updated).getTime();
            const dateEndReturn = new Date(timeDelivered + timeOneDay * 14);

            if (dateEndReturn < new Date()) {
              const date = dateEndReturn.toLocaleDateString("en-EN", {
                month: "long",
                day: "2-digit",
                year: "numeric",
              });

              elements.push(
                <p className={cn(Styles.windowCloseText, "my-3")}>
                  Return window closed on {date}
                </p>
              );
            }
          }

          elements.push(
            <div
              className={cn(
                "d-md-flex",
                "justify-content-center",
                "justify-content-lg-start",
                "mb-3",
                "mb-md-4"
              )}
              key={`row-item-${index}`}
            >
              <a
                className={"text-decoration-none me-md-10 mb-3 mb-md-0 d-block"}
                href={item.url}
              >
                <Button className={"w-md-auto"}>buy again</Button>
              </a>

              <Link href={`/create-review/${item.productId}`}>
                <a className={"text-decoration-none"}>
                  <Button className={"w-md-auto"} theme={ETheme.outlined}>
                    write a product review
                  </Button>
                </a>
              </Link>
            </div>
          );

          return elements;
        }}
      />

      <TableFooter
        paymentStatus={group.paymentStatus}
        shippingStatus={group.shippingStatus}
        regularShipping={group.shippingGross}
        salesTax={group.totalPst}
        vatTax={group.totalTax}
        subtotal={group.totalGross}
      />
    </>
  );
};

export default ShippingTable;
