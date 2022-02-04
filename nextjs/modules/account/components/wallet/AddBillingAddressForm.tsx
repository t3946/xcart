import React, { useContext } from "react";
import { FormInput } from "@modules/account/components/shared/FormInput";
import Select from "@modules/ui/forms/select/Select";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import { Form, Formik } from "formik";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "@modules/account/ts/consts/add-address-form";
import { getStates } from "@modules/account/utils/get-states";
import { useDispatch, useSelector } from "react-redux";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import { WalletCardsDialogContext } from "@modules/account/contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import {
  addCard,
  addDataFromSubmitCardForm,
} from "@redux/actions/account-actions/PaymentsActions";
import Store from "@redux/stores/Store";
import StoreInterface from "@modules/account/ts/types/store.type";
import FormGroup from "@modules/ui/forms/FormGroup";
import Input from "@modules/ui/forms/Input";

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
  React.useEffect(() => {
    dispatch(getTerritory());
  }, []);
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
          window.location.reload();
        }
      )
    );
  };

  return (
    <div className="billing-address-container px-3">
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
              <FormGroup
                input={
                  <Select
                    options={countries}
                    value={values.country}
                    onChange={(e) => {
                      setFieldValue("country", e.target.value);
                      setFieldValue("state", initialAddAddressFormValue.state);
                    }}
                    name={"state"}
                    isValid={!!touched.country && !errors.country?.value}
                    isInvalid={!!touched.country && !!errors.country?.value}
                  />
                }
                label="Country"
                error={!!touched.country && errors.country?.value}
              />

              <FormGroup
                input={
                  <Input
                    value={values.full_name}
                    name={"full_name"}
                    onChange={handleChange}
                    placeholder={"Albert H. Einstein"}
                    isValid={!!touched.full_name && !errors.full_name}
                    isInvalid={!!touched.full_name && !!errors.full_name}
                  />
                }
                label="Full Name (First and Last name)"
                error={!!touched.full_name && errors.full_name}
              />

              <FormGroup
                input={
                  <FormInputPhone
                    setFieldValue={setFieldValue}
                    handleChange={handleChange}
                    touched={touched}
                    errors={errors}
                    name={"phone_number"}
                    values={{
                      // phoneCountryCode: values.phoneCountryCode,
                      phone: values.phone_number,
                      phoneExt: values.phone_ext,
                    }}
                    mode={"ext"}
                  />
                }
                label="Phone Number"
              />

              <FormGroup
                input={
                  <Input
                    value={values.street}
                    name={"street"}
                    onChange={handleChange}
                    placeholder={"Street address or P.O. Box"}
                    isValid={!!touched.street && !errors.street}
                    isInvalid={!!touched.street && !!errors.street}
                  />
                }
                label="Address"
                error={!!touched.street && errors.street}
              />

              <FormGroup
                input={
                  <Input
                    value={values.detailed}
                    name={"detailed"}
                    onChange={handleChange}
                    placeholder={"Apt, suite, unit, building, floor, etc."}
                    isValid={!!touched.detailed && !errors.detailed}
                    isInvalid={!!touched.detailed && !!errors.detailed}
                  />
                }
                label=""
                error={!!touched.detailed && errors.detailed}
              />

              <FormGroup
                input={
                  <Input
                    value={values.city}
                    name={"city"}
                    onChange={handleChange}
                    placeholder={"Jackson"}
                    isValid={!!touched.city && !errors.city}
                    isInvalid={!!touched.city && !!errors.city}
                  />
                }
                label="City"
                error={!!touched.city && errors.city}
              />

              <FormGroup
                input={
                  <Select
                    options={getStates(states, values.country.value)}
                    value={values.state}
                    onChange={handleChange}
                    name={"state"}
                    isValid={!!touched.state && !errors.state?.value}
                    isInvalid={!!touched.state && !!errors.state?.value}
                  />
                }
                label="State/Province"
                error={!!touched.state && errors.state?.value}
              />

              <FormGroup
                input={
                  <Input
                    value={values.zip}
                    name={"zip"}
                    onChange={handleChange}
                    placeholder={"39213"}
                    isValid={!!touched.zip && !errors.zip}
                    isInvalid={!!touched.zip && !!errors.zip}
                  />
                }
                label="Zip/Postal Code"
                error={!!touched.zip && errors.zip}
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

export default AddBillingAddressForm;
