import React, { useState } from "react";
import { AddAddressForm } from "@modules/account/components/addresses/AddAddressForm";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";
import { useRouter } from "next/router";
import { ApiService } from "@modules/shared/services/api.service";
import Store from "@redux/stores/Store";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { useDispatch } from "react-redux";
import { BillingAddressList } from "@modules/account/components/wallet/BillingAddressList";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import { Formik, Form, FormikHelpers } from "formik";
import { editShippingAddress } from "@redux/actions/account-actions/OrdersActions";

interface ChangeAddressProps {
  handleClose?: () => void;
}

export const ChangeAddress: React.FC<ChangeAddressProps> = ({
  handleClose,
}) => {
  const dispatch = useDispatch();
  const userId = useSelectorAccount((e) => {
    return e.user?.user_id;
  });
  React.useEffect(() => {
    dispatch(getAddresses(userId));
    dispatch(getTerritory());
  }, []);

  const addresses = useSelectorAccount((e) =>
    e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.SHIPPING
    )
  );

  const initialValues = {
    address: "",
  };

  const [isAddingAddress, setIsAddingAddress] = useState(false);

  const apiService = new ApiService();

  const breakpoint = useBreakpoint();

  const router = useRouter();
  const urlParams = router.query;

  const onChangeAddress = (
    values: Record<any, any>,
    { setSubmitting }: FormikHelpers<any>
  ) => {
    const address = Store.getState().addresses.addressesList.find(
      (address) => address.address_id === parseInt(values.address)
    );

    dispatch(
      editShippingAddress({
        data: {
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
        },
        success(response) {
          setSubmitting(false);
          handleClose && handleClose();
        },
      })
    );
  };

  return isAddingAddress ? (
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
    <Formik onSubmit={onChangeAddress} initialValues={initialValues}>
      {({ handleChange, isSubmitting, values }) => {
        return (
          <Form>
            <div className="billing-address-container">
              <div className="dialog-title">Select a shipping address</div>
              {addresses && (
                <BillingAddressList
                  value={values.address}
                  onChange={handleChange}
                  addresses={addresses}
                  disabled={isSubmitting}
                />
              )}

              <div className="billing-address-butns">
                <button
                  type={"button"}
                  onClick={() => setIsAddingAddress(true)}
                  className="form-button account-submit-btn account-submit-btn-outline auto-width-button add-billing-address-btn"
                >
                  ADD new ADDRESS
                </button>
                <button
                  disabled={!values.address || isSubmitting}
                  type={"submit"}
                  className="form-button account-submit-btn auto-width-button"
                >
                  {isSubmitting ? "PEnding..." : "use this address"}
                </button>
              </div>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};
