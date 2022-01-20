import React from "react";
import Table, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import TableFooter from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/TableFooter";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";

interface IProps {
  shipping: {
    city: string;
    state: string;
    country: string;
    regularShipping: number;
    salesTax: number;
    vatTax: number;
    subtotal: number;
    paymentStatus: string;
    shippingStatus: string;
    items: {
      name: string;
      sku: string;
      price: number;
      amount: number;
      total?: number;
      image: string;
    }[];
  };
}

const ShippingTable: React.FC<IProps> = ({ shipping }) => {
  const items = shipping.items.map((item) => {
    const total = parseFloat((item.price * item.amount).toFixed(2));
    return { ...item, total };
  });
  return (
    <>
      <div className={cn([Styles.table__name, Styles.tableName])}>
        The items below are shipped from {shipping.city}, {shipping.state},{" "}
        {shipping.country}
      </div>

      <Table tableType={TableTypes.increaseInShippingCharge} items={items} />
      <TableFooter
        paymentStatus={shipping.paymentStatus}
        shippingStatus={shipping.shippingStatus}
        regularShipping={shipping.regularShipping}
        salesTax={shipping.salesTax}
        vatTax={shipping.vatTax}
        subtotal={shipping.subtotal}
      />
    </>
  );
};

export default ShippingTable;
