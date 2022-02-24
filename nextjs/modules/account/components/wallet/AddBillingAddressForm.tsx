import React, { useContext } from "react";
import Select from "@modules/ui/forms/select/Select";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import { getCountryByCode } from "@utils/Countries";
import { Form, Formik } from "formik";
import {
  getAddAddressFormValidationSchema,
  initialAddAddressFormValue,
} from "@modules/account/ts/consts/add-address-form";
import { getStates } from "@modules/account/utils/get-states";
import { useDispatch } from "react-redux";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import { WalletCardsDialogContext } from "@modules/account/contexts/WalletCardsDialogContext";
import FormGroup from "@modules/ui/forms/FormGroup";
import Input from "@modules/ui/forms/Input";
import Button, { ETheme } from "@modules/ui/forms/Button";
import {
  addAddress,
  editAddress,
} from "@redux/actions/account-actions/AddressActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import ErrorFocus from "@components/common/form-validation-focus/focusFormikComponent";

interface IProps {
  onCancel: () => void;
  onSubmitted: () => void;
}

export const AddBillingAddressForm: React.FC<IProps> = (props) => {
  const { onCancel, onSubmitted } = props;
  const dispatch = useDispatch();
  const context = useContext(WalletCardsDialogContext);
  const countryPhoneCodes = useSelectorAccount((e) => e.main.countries);
  const countries = useSelectorAccount((e) => e.countries);
  const submitCardFormLoading = useSelectorAccount(
    (e) => e.payments.submitCardFormLoading
  );
  const states = useSelectorAccount((e) => e.main.states);
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    dispatch(getTerritory());
  }, []);

  const onSubmit = (values) => {
    const phoneCode = getCountryByCode(
      values.phone_numberCode,
      countries
    ).phone_code;
    const newAddress = {
      ...values,
      phone_number: `+${phoneCode}${values.phone_number.replace(
        /[+()\-\s]/gim,
        ""
      )}`,
      country: values.country.value,
      state: values.state.value,
      address_type: "billing",
    };

    dispatch(addAddress(newAddress, onSubmitted, user.userId));
  };

  return (
    <div className="billing-address-container px-3">
      <div className="dialog-title">Add a billing address</div>
      <Formik
        initialValues={initialAddAddressFormValue}
        onSubmit={onSubmit}
        validationSchema={getAddAddressFormValidationSchema(states)}
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
              <ErrorFocus />
              <FormGroup
                input={
                  <Select
                    name="country"
                    clearable={false}
                    options={countryPhoneCodes}
                    value={values.country}
                    onChange={(e) => {
                      setFieldValue("country", e.target.value);
                      setFieldValue("state", initialAddAddressFormValue.state);
                    }}
                    isValid={!!touched.country && !errors.country}
                    isInvalid={!!touched.country && !!errors.country}
                  />
                }
                label="Country"
                error={!!touched.country && errors.country}
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
                    values={values}
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
                    clearable={false}
                    options={getStates(states, values.country.value)}
                    value={values.state}
                    onChange={handleChange}
                    name={"state"}
                    isValid={!!touched.state && !errors.state}
                    isInvalid={!!touched.state && !!errors.state}
                  />
                }
                label="State/Province"
                error={!!touched.state && errors.state}
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
                <div className="d-flex">
                  <Button
                    onClick={onCancel}
                    theme={ETheme.outlined}
                    type={"button"}
                    disabled={submitCardFormLoading}
                    className={"me-2"}
                  >
                    back
                  </Button>

                  <Button disabled={submitCardFormLoading} type={"submit"}>
                    use this address
                  </Button>
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
