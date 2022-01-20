import React, { useContext } from "react";
import { FormInput } from "../shared/FormInput";
import FormSelect from "@modules/ui/forms/Select";
import { Form, Formik } from "formik";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "../../ts/consts/add-address-form";
import { useDispatch, useSelector } from "react-redux";
import { getStates } from "../../utils/get-states";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import {
  addCard,
  addDataFromSubmitCardForm,
} from "../../../../redux/actions/account-actions/PaymentsActions";
import Store from "@redux/stores/Store";
import StoreInterface from "@modules/account/ts/types/store.type";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";

interface AddBillingAddressFormProps {
  edit: boolean;
}

export const AddBillingAddressForm: React.FC<AddBillingAddressFormProps> = ({
  edit,
}) => {
  const dispatch = useDispatch();
  const context = useContext(WalletCardsDialogContext);

  const countries = useSelector((e: StoreInterface) => e.main.countries);

  const submitCardFormLoading = useSelector(
    (e: StoreInterface) => e.payments.submitCardFormLoading
  );

  const cardSubmitData = useSelector(
    (e: StoreInterface) => e.payments.submitFormData
  );

  const states = useSelector((e: any) => e.main.states);

  const { showSnackbar } = useContext(SnackbarContext);

  const onSubmit = (values) => {
    const newAddress = {
      ...values,
      country: values.country.value,
      state: values.state.value,
    };

    if (edit) {
      dispatch(
        addDataFromSubmitCardForm({
          address: newAddress,
        })
      );
      context.setContent(BillingAddressFormEnum.EDIT);
      return;
    }

    dispatch(
      addCard(
        {
          ...cardSubmitData,
          address: newAddress,
          userId: Store.getState().user.id,
        },
        () => {
          context.handleClose();
          showSnackbar({
            header: "Success",
            message: "New card added in your wallet!",
            theme: "success",
          });
        }
      )
    );
  };
  return (
    <div className="billing-address-container add-billing-address-container">
      <div className="dialog-title">Add a billing address</div>
      <Formik
        initialValues={initialAddAddressFormValue}
        onSubmit={onSubmit}
        validationSchema={addAddressFormValidationSchema}
      >
        {({
          errors,
          setFieldValue,
          values,
          touched,
          handleChange,
          handleBlur,
        }) => {
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <FormSelect
                items={countries}
                value={values.country}
                label={"Country"}
                classes={{
                  input: "add-address-input",
                  group: "form-input-container",
                }}
                onClick={(value) => {
                  setFieldValue("country", value);
                  setFieldValue("state", initialAddAddressFormValue.state);
                }}
                name={"state"}
                id="add-billing-address-country"
              />
              <FormInput
                label={"Full Name (First and Last name)"}
                placeholder={"Albert H. Einstein"}
                value={values.full_name}
                name={"full_name"}
                errorMessage={errors.full_name}
                handleChange={handleChange}
                touched={touched.full_name}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
              />
              <FormInput
                label={"Phone Number"}
                value={values.phone_number}
                name={"phone_number"}
                errorMessage={errors.phone_number}
                handleChange={handleChange}
                touched={touched.phone_number}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
                mask={"+9 (999) 999 99 99"}
              />
              <FormInput
                placeholder="Street address or P.O. Box"
                label={"Address"}
                value={values.street}
                name={"street"}
                errorMessage={errors.street}
                handleChange={handleChange}
                touched={touched.street}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
              />
              <FormInput
                placeholder="Apt, suite, unit, building, floor, etc."
                value={values.detailed}
                name={"detailed"}
                errorMessage={errors.detailed}
                handleChange={handleChange}
                touched={touched.detailed}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
              />
              <FormInput
                label={"City"}
                placeholder="Jackson"
                value={values.city}
                name={"city"}
                errorMessage={errors.city}
                handleChange={handleChange}
                touched={touched.city}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
              />
              <FormSelect
                classes={{
                  input: "add-address-input",
                  group: "form-input-container",
                }}
                items={getStates(states, values.country.value)}
                value={values.state}
                label={"State/Province"}
                onClick={(value) => setFieldValue("state", value)}
                name={"state"}
                id="add-billing-address-state"
              />
              <FormInput
                label={"Zip/Postal Code"}
                placeholder="39213"
                value={values.zip}
                errorMessage={errors.zip}
                name={"zip"}
                handleChange={handleChange}
                touched={touched.zip}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
              />
              <div className="billing-address-add-btns">
                <div className="billing-address-add-btns-container">
                  <button
                    onClick={() =>
                      context.setContent(BillingAddressFormEnum.LIST_ADDRESS)
                    }
                    type={"submit"}
                    disabled={submitCardFormLoading}
                    className="form-button account-submit-btn account-submit-btn-outline auto-width-button billing-address-back-btn"
                  >
                    Back
                  </button>
                  <button
                    disabled={submitCardFormLoading}
                    type={"submit"}
                    className="form-button account-submit-btn auto-width-button"
                  >
                    USE tHIS aDDRESS
                  </button>
                </div>
              </div>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
