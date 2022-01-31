import React, { Fragment } from "react";
import { Grid, Typography } from "@mui/material";
import { EmptyRow } from "@admin/modules/order-fraud/components/related-orders-section/empty-row/EmptyRow";
import { useSelector } from "react-redux";
import {
  FraudCheckStore,
  RelatedOrderItem,
} from "@admin/modules/order-fraud/ts/types/redux";
import { RelatedOrderItems } from "@admin/modules/order-fraud/components/related-orders-section/related-order-item/RelatedOrderItems";
import { RelatedGroupItem } from "@admin/modules/order-fraud/components/related-orders-section/related-group-item/RelatedGroupItem";

export const RelatedOrdersSection: React.FC = () => {
  const contactInformation = [
    { label: "Full name", attr: "firstname" },
    { label: "Phone", attr: "phone" },
    { label: "Email", attr: "email" },
  ];
  const shippingInformation = [
    { label: "Full name", attr: "s_firstname" },
    { label: "Company", attr: "s_company" },
    { label: "Address", attr: "s_address" },
  ];
  const billingInformation = [
    { label: "Full name", attr: "b_firstname" },
    { label: "Company", attr: "b_company" },
    { label: "Address", attr: "b_address" },
  ];
  const info = useSelector((state: FraudCheckStore) => state.data.attributes);
  const groups = useSelector((state: FraudCheckStore) => state.data.groups);
  if (!info) {
    return;
  }
  return (
    <div className="table-wrapper__fraud-check-question">
      <Typography mt={1} variant="h6" align="center">
        Related orders
      </Typography>
      <table border={1}>
        <tr className="table-related-order-head">
          <th>Section</th>
          <th className="field-name">Field A</th>
          <th className="item-fraud">Fraud orders with the same Field A</th>
          <th>Cleared orders with the same Field A</th>
        </tr>
        <EmptyRow label="Contact Information" />
        {contactInformation.map((item) => (
          <tr>
            <td />
            <td>{item.label}</td>
            <RelatedOrderItems orders={info[item.attr]} isFraud />
            <RelatedOrderItems orders={info[item.attr]} />
          </tr>
        ))}
        <EmptyRow />
        <EmptyRow label="Shipping Address" />
        {shippingInformation.map((item) => (
          <tr>
            <td />
            <td>{item.label}</td>
            <RelatedOrderItems orders={info[item.attr]} isFraud />
            <RelatedOrderItems orders={info[item.attr]} />
          </tr>
        ))}
        <EmptyRow />
        <tr>
          <td />
          <td>Customer's IP</td>
          <RelatedOrderItems orders={info.ip_location} isFraud />
          <RelatedOrderItems orders={info.ip_location} />
        </tr>
        <EmptyRow />
        <EmptyRow label="Billing Address" />
        {billingInformation.map((item) => (
          <tr>
            <td />
            <td>{item.label}</td>
            <RelatedOrderItems orders={info[item.attr]} isFraud />
            <RelatedOrderItems orders={info[item.attr]} />
          </tr>
        ))}
        {groups.map((group) => (
          <Fragment>
            <EmptyRow />
            <EmptyRow label={group.dx} />
            <RelatedGroupItem group={group} />
          </Fragment>
        ))}
      </table>
    </div>
  );
};
