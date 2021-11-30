import React from "react";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ChangeAddress } from "@modules/account/components/orders/ChangeAddress";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useHistory, useParams } from "react-router-dom";
import { OrderPageURLParams } from "@modules/account/ts/types/order-page-url-params.type";

interface OrderAddressesPageProps {
  orderItem?: any;
}

export const OrderAddressesPage: React.FC<OrderAddressesPageProps> = ({
  orderItem,
}) => {
  const changeShippingAddressDialog = useDialog();

  const breakpoint = useBreakpoint();

  const history = useHistory();

  const params = useParams<OrderPageURLParams>();
  return (
    <div>
      <div className="page-label">Addresses and contacts</div>
      <div className="order-addresses">
        <div className="order-address-block">
          <div className="order-address-block-title">Contact information</div>
          <div className="order-address-text-block">
            <div className="order-address-text-block-label">Full Name:</div>
            <div className="order-address-text-block-info">
              {orderItem.orderInfo.firstname}
            </div>
          </div>
          <div className="order-address-text-block">
            <div className="order-address-text-block-label">Phone:</div>
            <div className="order-address-text-block-info">
              {orderItem.orderInfo.phone}{" "}
              {orderItem.orderInfo.phone_ext &&
                `ext ${orderItem.orderInfo.phone_ext}`}
            </div>
          </div>
          <div className="order-address-text-block">
            <div className="order-address-text-block-label">Email:</div>
            <div className="order-address-text-block-info">
              {orderItem.orderInfo.email}
            </div>
          </div>
        </div>
        <hr className="order-address-block-underline" />
        <div className="order-address-block address-list">
          <div className="order-address-item">
            <div className="order-address-block-title">Shipping address</div>
            <div className="order-address">
              <div>
                <div className="order-address-text-block-label">
                  {orderItem.orderInfo.s_firstname}
                </div>
                <div className="order-address-text">
                  {orderItem.orderInfo.s_zipcode} {orderItem.orderInfo.s_city}{" "}
                  {orderItem.orderInfo.s_address}
                </div>
              </div>
              <button
                onClick={() =>
                  breakpoint({
                    xs: () =>
                      history.push(
                        `/account/orders/${params.id}/${params.orderType}/change-address`
                      ),
                    md: changeShippingAddressDialog.handleClickOpen,
                  })
                }
                className="form-button order-change-address-btn"
              >
                Change shipping address
              </button>
            </div>
          </div>
          <div className="order-address-item">
            <div className="order-address-block-title">Billing address</div>
            <div className="order-address">
              <div>
                <div className="order-address-text-block-label">
                  {orderItem.orderInfo.b_firstname}
                </div>
                <div className="order-address-text">
                  {orderItem.orderInfo.b_zipcode} {orderItem.orderInfo.b_city}{" "}
                  {orderItem.orderInfo.b_address}
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr className="order-address-block-underline" />
        <div className="order-address-block footer-block">
          <div className="order-purchase-info">
            <div className="order-address-block-title">
              Purchase order information
            </div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">PO number:</div>{" "}
              <div className="order-address-text-block-info">
                {orderItem.orderInfo.po_number}
              </div>
            </div>
            <div className="d-flex justify-content-between">
              <div className="order-address-text-block-label">
                Company name:
              </div>
              <div className="order-address-text-block-info">Eureka Inc.</div>
            </div>
          </div>
          <div className="order-addresses-footer-info">
            <div className="order-purchase-info">
              <div className="order-address-block-title">Purchase manager</div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Full name:</div>
                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Phone:</div>
                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Fax:</div>

                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Email:</div>
                <div className="order-address-text-block-info">Eureka Inc.</div>
              </div>
            </div>
            <div className="order-purchase-info">
              <div className="order-address-block-title">Accounts payable</div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Full name:</div>
                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Phone:</div>
                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Fax:</div>
                <div className="order-address-text-block-info">464564</div>
              </div>
              <div className="d-flex justify-content-between">
                <div className="order-address-text-block-label">Email:</div>
                <div className="order-address-text-block-info">Eureka Inc.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <BootstrapDialogHOC
        show={changeShippingAddressDialog.open}
        title={"Change address"}
        onClose={changeShippingAddressDialog.handleClose}
      >
        <ChangeAddress handleClose={changeShippingAddressDialog.handleClose} />
      </BootstrapDialogHOC>
    </div>
  );
};
