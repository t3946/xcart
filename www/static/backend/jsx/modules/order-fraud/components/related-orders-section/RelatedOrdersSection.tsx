import React, { Fragment, useEffect } from "react";
import { Skeleton, Typography } from "@mui/material";
import { EmptyRow } from "@admin/modules/order-fraud/components/related-orders-section/empty-row/EmptyRow";
import { useDispatch, useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { RelatedOrderItems } from "@admin/modules/order-fraud/components/related-orders-section/related-order-item/RelatedOrderItems";
import { RelatedGroupItem } from "@admin/modules/order-fraud/components/related-orders-section/related-group-item/RelatedGroupItem";
import { fetchRelatedData } from "@redux/actions/fraudCheckActions";

export const RelatedOrdersSection: React.FC = () => {
  const dispatch = useDispatch();
  const orderId = useSelector((state: FraudCheckStore) => state.orderId);
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
  useEffect(() => {
    dispatch(fetchRelatedData(orderId));
  }, []);
  const relatedData = useSelector(
    (state: FraudCheckStore) => state.relatedData
  );

  return (
    <div className="table-wrapper__fraud-check-question">
      <Typography mt={1} variant="h6" align="center">
        Related orders
      </Typography>
      {relatedData ? (
        <table border={1}>
          <tr className="table-related-order-head">
            <th>Section</th>
            <th className="field-name">Field A</th>
            <th className="item-fraud">Fraud orders with the same Field A</th>
            <th>Cleared orders with the same Field A</th>
            <th>Other orders with the same Field A</th>
          </tr>
          <EmptyRow label="Contact Information" />
          {contactInformation.map((item) => (
            <tr>
              <td />
              <td>{item.label}</td>
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="fraud"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="cleared"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="other"
              />
            </tr>
          ))}
          <EmptyRow />
          <EmptyRow label="Shipping Address" />
          {shippingInformation.map((item) => (
            <tr>
              <td />
              <td>{item.label}</td>
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="fraud"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="cleared"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="other"
              />
            </tr>
          ))}
          <EmptyRow />
          <tr>
            <td />
            <td>Customer's IP</td>
            <RelatedOrderItems
              orders={relatedData.attributes.ip_location}
              type="fraud"
            />
            <RelatedOrderItems
              orders={relatedData.attributes.ip_location}
              type="cleared"
            />
            <RelatedOrderItems
              orders={relatedData.attributes.ip_location}
              type="other"
            />
          </tr>
          <EmptyRow />
          <EmptyRow label="Billing Address" />
          {billingInformation.map((item) => (
            <tr>
              <td />
              <td>{item.label}</td>
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="fraud"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="cleared"
              />
              <RelatedOrderItems
                orders={relatedData.attributes[item.attr]}
                type="other"
              />
            </tr>
          ))}
          {relatedData.groups.map((group) => (
            <Fragment>
              <EmptyRow />
              <EmptyRow label={group.dx} />
              <RelatedGroupItem group={group} />
            </Fragment>
          ))}
        </table>
      ) : (
        <Skeleton variant="rectangular" width="100%" height={500} />
      )}
    </div>
  );
};
