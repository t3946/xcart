import React from "react";

interface IProps {
  order: any;
}

export const PurchaseOrderInformation: React.FC<IProps> = (props) => {
  const { order } = props;
  const po = order.extra.purchase_order;

  return (
    <div className="order-information">
      <div className={"transaction-order-info-container"}>
        <div className="transaction-shipping-address">
          <div className="transaction-top-info-left-part-label">
            Purchase order information
          </div>
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">PO number:</p>
            <p className="left-part">{po.po_number}</p>
          </div>
          {po.company_name && (
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Company name:</p>
              <p className="left-part">{po.company_name}</p>
            </div>
          )}
        </div>
      </div>
      <div className={"transaction-order-info-container"}>
        <div className="transaction-shipping-address">
          <div className="transaction-top-info-left-part-label">
            Purchase manager
          </div>
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Full name:</p>
            <p className="left-part">{po.name_of_purchaser}</p>
          </div>
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Phone:</p>
            <p className="left-part">{po.purchase_manager_phone}</p>
          </div>
          {po.purchase_manager_fax && (
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Fax:</p>
              <p className="left-part">{po.purchase_manager_fax}</p>
            </div>
          )}
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Email:</p>
            <p className="left-part">{po.purchase_manager_email}</p>
          </div>
        </div>
        <div className="transaction-billing-address">
          <div className="transaction-top-info-left-part-label">
            Accounts payable
          </div>
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Full name:</p>
            <p className="left-part">{po.accounts_payable_full_name}</p>
          </div>
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Phone:</p>
            <p className="left-part">{po.accounts_payable_phone}</p>
          </div>
          {po.accounts_payable_fax && (
            <div className="info-item-container info-item-container-spacing">
              <p className="label-info-item right-part">Fax:</p>
              <p className="left-part">{po.accounts_payable_fax}</p>
            </div>
          )}
          <div className="info-item-container info-item-container-spacing">
            <p className="label-info-item right-part">Email:</p>
            <p className="left-part">{po.accounts_payable_email}</p>
          </div>
        </div>
      </div>
    </div>
  );
};
