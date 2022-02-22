import React from "react";
import cn from "classnames";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ChangeAddress } from "@modules/account/components/orders/ChangeAddress";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import { useRouter } from "next/router";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import HighlightCheckbox from "@modules/account/components/orders/Decision/CustomDuties/HighlightCheckbox";

import StylesAddresses from "@modules/account/pages/Addresses.module.scss";
import Styles from "@modules/account/pages/OrderAddressesPage.module.scss";

interface OrderAddressesPage {
  orderItem: OrderView;
}

export const OrderAddressesPage: React.FC<OrderAddressesPage> = ({
  orderItem,
}) => {
  console.log({ orderItem });
  const changeShippingAddressDialog = useDialog();

  function shippingAddress() {
    const parts = [
      orderItem.address.shippingZip || "",
      orderItem.address.shippingCity || "",
      orderItem.address.shippingAddress || "",
    ];

    const address = parts.join(" ").trim();

    return address || "No shipping address";
  }

  function billingAddress() {
    const parts = [
      orderItem.address.billingZip || "",
      orderItem.address.billingCity || "",
      orderItem.address.billingAddress || "",
    ];

    const address = parts.join(" ").trim();

    return address || "No billing address";
  }

  function phone() {
    const parts = [orderItem.client.phone || ""];

    if (orderItem.client.phoneExt) {
      parts.push("ext " + orderItem.client.phoneExt);
    }

    const phone = parts.join(" ").trim();

    return phone || "No phone";
  }

  const gridItems = [
    <div>
      <div className="order-address-block-title">Contact information</div>
      <div className="px-3 px-md-0">
        <div className="order-address-text-block">
          <div className="order-address-text-block-label">Full Name:</div>
          <div className="order-address-text-block-info">
            {orderItem.client.firstName || "No name"}
          </div>
        </div>
        <div className="order-address-text-block">
          <div className="order-address-text-block-label">Phone:</div>
          <div className="order-address-text-block-info">{phone()}</div>
        </div>
        <div className="order-address-text-block">
          <div className="order-address-text-block-label">Email:</div>
          <div className="order-address-text-block-info">
            {orderItem.client.email || "No email"}
          </div>
        </div>
      </div>
    </div>,

    <div className="address-list">
      <div className={Styles.orderAddressItem}>
        <div className="order-address-block-title">Shipping address</div>
        <div className={Styles.orderAddress}>
          <div>
            <div className="order-address-text-block-label">
              {orderItem.client.shippingFirstName}
            </div>
            <div className="order-address-text">{shippingAddress()}</div>
          </div>
          <button
            onClick={changeShippingAddressDialog.handleClickOpen}
            className="form-button order-change-address-btn"
          >
            Change shipping address
          </button>
        </div>
      </div>
      <div className={Styles.orderAddressItem}>
        <div className="order-address-block-title">Billing address</div>
        <div className={Styles.orderAddress}>
          <div>
            <div className="order-address-text-block-label">
              {orderItem.client.billingName}
            </div>
            <div className="order-address-text">{billingAddress()}</div>
          </div>
        </div>
      </div>
    </div>,
  ];

  !!orderItem.purchase &&
    gridItems.push(
      <div className="footer-block">
        <div className="order-purchase-info">
          <div className="order-address-block-title">
            Purchase order information
          </div>
          <div className="d-flex justify-content-between">
            <div className="order-address-text-block-label">PO number:</div>
            <div className="order-address-text-block-info">
              {orderItem.purchase.poNumber}
            </div>
          </div>
          <div className="d-flex justify-content-between">
            <div className="order-address-text-block-label">Company name:</div>
            <div className="order-address-text-block-info">
              {orderItem.purchase.company}
            </div>
          </div>
        </div>
        <div className="order-addresses-footer-info">
          <div className="order-purchase-info">
            <div className="order-address-block-title">Purchase manager</div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Full name:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.managerName}
              </div>
            </div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Phone:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.managerPhone}
              </div>
            </div>
            {orderItem.purchase.managerFax && (
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Fax:</div>
                <div className="order-address-text-block-info">
                  {orderItem.purchase.managerFax}
                </div>
              </div>
            )}
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Email:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.managerEmail}
              </div>
            </div>
          </div>
          <div className="order-purchase-info">
            <div className="order-address-block-title">Accounts payable</div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Full name:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.accountsPayableName}
              </div>
            </div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Phone:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.accountsPayablePhone}
              </div>
            </div>
            {orderItem.purchase.accountsPayableFax && (
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Fax:</div>
                <div className="order-address-text-block-info">
                  {orderItem.purchase.accountsPayableFax}
                </div>
              </div>
            )}
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">Email:</div>
              <div className="order-address-text-block-info">
                {orderItem.purchase.accountsPayableEmail}
              </div>
            </div>
          </div>
        </div>
      </div>
    );

  return (
    <div>
      <div className="page-label">Addresses and contacts</div>
      <GreyGrid
        classes={{
          list: ["mb-20", Styles.order__grid, Styles.orgerGrid],
          item: Styles.orderGridItem,
        }}
        items={gridItems}
      />
      {orderItem.non_us_confirmation && (
        <HighlightCheckbox
          className={"mt-4 mb-4"}
          onChange={() => {}}
          checked={true}
          disabled={false}
          label="I agree to be responsible for custom duties, CODs, and other charges associated with bringing goods to Canada."
        />
      )}

      <BootstrapDialogHOC
        classes={{
          modal: [StylesAddresses.modalBody, StylesAddresses.modalWidth],
        }}
        show={changeShippingAddressDialog.open}
        title={"Change address"}
        onClose={changeShippingAddressDialog.handleClose}
      >
        <ChangeAddress handleClose={changeShippingAddressDialog.handleClose} />
      </BootstrapDialogHOC>
    </div>
  );
};
