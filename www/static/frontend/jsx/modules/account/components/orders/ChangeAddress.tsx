import React, { useState } from "react";
import { BillingAddressList } from "@client/modules/account/components/wallet/BillingAddressList";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/store.type";
import { AddAddressForm } from "@client/modules/account/components/addresses/AddAddressForm";
import { MobileMenuBackBtn } from "@client/modules/account/pages/MobileMenuBackBtn";
import { useHistory, useParams } from "react-router-dom";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";
import { ApiService } from "@client/modules/shared/services/api.service";
import Store from "@client/jsx/redux/stores/Store";
import { setOrders } from "@client/jsx/redux/actions/account-actions/OrdersActions";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";

interface ChangeAddressProps {
  handleClose?: () => void;
}

export const ChangeAddress: React.FC<ChangeAddressProps> = ({
  handleClose,
}) => {
  const addresses = useSelector((e: AccountStore) => e.addresses.addressesList);
  const [isAddingAddress, setIsAddingAddress] = useState(false);

  const [selectedValue, setSelectedValue] = useState(null);

  const [loading, setLoading] = useState(false);

  const urlParams = useParams<OrderPageURLParams>();

  const apiService = new ApiService();

  const breakpoint = useBreakpoint();

  const history = useHistory();

  const onChangeAddress = () => {
    setLoading(true);
    const address = Store.getState().addresses.addressesList.find(
      (address) => address.address_id === selectedValue
    );

    apiService
      .post(
        "/account/api/orders/edit-shipping-address",
        JSON.stringify({
          order_id: urlParams.id,
          addressData: {
            s_address: address.street,
            s_city: address.city,
            s_full_address:
              address.country.value +
              " " +
              address.state.value +
              " " +
              address.zip +
              " " +
              address.street,
            s_firstname: address.full_name,
            s_zipcode: address.zip,
            s_state: address.state.value,
            s_country: address.country.value,
          },
        })
      )
      .then((response) => {
        setLoading(false);
        breakpoint({
          xs: () =>
            history.push(
              `/orders/${urlParams.id}/${urlParams.orderType}/order-info/addresses`
            ),
          md: handleClose,
        });
        handleClose();
        setOrders(
          Store.getState().ordersStore.orders[urlParams.orderType].map((e) => {
            if (e.orderid === urlParams.id) {
              return {
                ...e,
                response,
              };
            }
          }),
          urlParams.orderType
        );
      });
  };
  return (
    <>
      <MobileMenuBackBtn
        redirectUrl={`/orders/${urlParams.id}/${urlParams.orderType}/order-info/addresses`}
        label={"Addresses"}
      />

      {isAddingAddress ? (
        <AddAddressForm onCancelClick={() => setIsAddingAddress(false)}>
          <button
            style={{ marginTop: 10 }}
            onClick={() => setIsAddingAddress(false)}
            className="form-button account-submit-btn account-submit-btn-outline"
          >
            Back
          </button>
        </AddAddressForm>
      ) : (
        <div className="billing-address-container">
          <div className="dialog-title">Select a shipping address</div>
          {addresses && (
            <BillingAddressList
              value={selectedValue}
              setValue={setSelectedValue}
              addresses={addresses}
            />
          )}

          <div className="billing-address-butns">
            <button
              type={"submit"}
              onClick={() => setIsAddingAddress(true)}
              className="form-button account-submit-btn account-submit-btn-outline auto-width-button add-billing-address-btn"
            >
              ADD new ADDRESS
            </button>
            <button
              disabled={!selectedValue || loading}
              type={"submit"}
              className="form-button account-submit-btn auto-width-button"
              onClick={onChangeAddress}
            >
              {loading ? "PEnding..." : "use this address"}
            </button>
          </div>
        </div>
      )}
    </>
  );
};
