import React, { useContext } from "react";
import FormSelect from "@modules/ui/forms/Select";
import { FormCheckBox } from "@modules/account/components/shared/FormCheckBox";
import { useFormik } from "formik";
import FormInputPhone from "@modules/account/components/shared/FormInputPhone";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "@modules/account/ts/consts/add-address-form";
import { useDispatch } from "react-redux";
import {
  addAddress,
  editAddress,
} from "@redux/actions/account-actions/AddressActions";
import { getStates } from "@modules/account/utils/get-states";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/AddAddressForm.module.scss";
import InputGroup from "./InputGroup";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Checkbox from "@modules/ui/forms/Checkbox";

export const AddAddressForm: React.FC<any> = ({
  addressInfo = undefined,
  onCancelClick = undefined,
  children,
}) => {
  const dispatch = useDispatch();
  const countries = useSelectorAccount((e) => e.main.countries);
  const states = useSelectorAccount((e) => e.main.states);
  const { showSnackbar } = useContext(SnackbarContext);
  const user = useSelectorAccount((e) => e.user);

  const addressFormLoading = useSelectorAccount(
    (e: any) => e.addresses.addressFormLoading
  );

  const onPended = () => {
    onCancelClick();
    showSnackbar({
      header: "Success",
      message: `${!addressInfo ? "Address added!" : "Address edit!"}`,
      theme: "success",
    });
  };

  const submitForm = () => {
    const newAddress = {
      ...formik.values,
      phone_number: formik.values.phone_number.replace(/[+()\-\s]/gim, ""),
      country: formik.values.country.value,
      state: formik.values.state.value,
    };

    if (addressInfo) {
      dispatch(editAddress(newAddress, onPended));
      return;
    }

    dispatch(addAddress(newAddress, onPended, user.userId));
  };

  const formik = useFormik({
    initialValues: addressInfo || initialAddAddressFormValue,
    validationSchema: addAddressFormValidationSchema,
    onSubmit: submitForm,
  });

  return (
    <div>
      <form
        onSubmit={formik.handleSubmit}
        className="your-order-form"
        encType="multipart/form-data"
      >
        <InputGroup
          label="Country"
          error={formik.touched.country?.value && formik.errors.country?.value}
          component={
            <FormSelect
              items={countries}
              value={formik.values.country}
              label={"Country"}
              onClick={(value) => {
                formik.setFieldValue("country", value);
                formik.setFieldValue("state", initialAddAddressFormValue.state);
                formik.setFieldValue("country", value);
              }}
              name={"country"}
              id={"add-address-country"}
              errorMessage={
                formik.touched.country?.value && formik.errors.country?.value
              }
            />
          }
        />
        <InputGroup
          label="Full Name (First and Last name)"
          placeholder={"Albert H. Einstein"}
          value={formik.values.full_name}
          name={"full_name"}
          error={formik.touched.full_name && formik.errors.full_name}
          isInvalid={!!(formik.touched.full_name && formik.errors.full_name)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          label="Phone Number"
          component={
            <FormInputPhone
              setFieldValue={formik.setFieldValue}
              handleChange={formik.handleChange}
              touched={formik.touched}
              errors={formik.errors}
              name={"phone_number"}
              values={{
                // phoneCountryCode: values.phoneCountryCode,
                phone: formik.values.phone_number,
                phoneExt: formik.values.phone_numberExt,
              }}
              mode={"ext"}
            />
          }
        />

        <InputGroup
          label="Address"
          value={formik.values.street}
          name={"street"}
          error={formik.touched.street && formik.errors.street}
          isInvalid={!!(formik.touched.street && formik.errors.street)}
          handleChange={formik.handleChange}
          touched={formik.touched.street}
          classes={{ group: "mb-1" }}
          handleBlur={formik.handleBlur}
        />

        <InputGroup
          placeholder="Apt, suite, unit, building, floor, etc."
          value={formik.values.detailed}
          name={"detailed"}
          error={formik.touched.detailed && formik.errors.detailed}
          isInvalid={!!(formik.touched.detailed && formik.errors.detailed)}
          handleChange={formik.handleChange}
          touched={formik.touched.detailed}
          classes={{ grid: "justify-content-end" }}
          handleBlur={formik.handleBlur}
        />

        <InputGroup
          label={"City"}
          placeholder="Jackson"
          value={formik.values.city}
          name={"city"}
          error={formik.touched.city && formik.errors.city}
          isInvalid={!!(formik.touched.city && formik.errors.city)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          label={"State/Province"}
          error={formik.touched.state && formik.errors.state?.value}
          component={
            <FormSelect
              items={getStates(states, formik.values.country.value)}
              value={formik.values.state}
              label={"State/Province"}
              onClick={(value) => {
                formik.setFieldValue("state", value);
                delete formik.errors.state;
              }}
              name={"state"}
              id={"add-address-state"}
              errorMessage={
                formik.touched.state?.value && formik.errors.state?.value
              }
            />
          }
        />

        <InputGroup
          label={"Zip/Postal Code"}
          placeholder="39213"
          value={formik.values.zip}
          name={"zip"}
          error={formik.touched.zip && formik.errors.zip}
          isInvalid={!!(formik.touched.zip && formik.errors.zip)}
          handleChange={formik.handleChange}
        />
        <InputGroup
          classNames={{ container: "m-0" }}
          component={
            <Checkbox
              label={
                <span className={Styles.checboxLabel}>
                  Make this my default address
                </span>
              }
              checked={formik.values.is_default}
              name={"is_default"}
              onChange={formik.handleChange}
              classes={{ container: "mt-20 mb-4" }}
            />
          }
        />
        <InputGroup
          classNames={{ container: "m-0" }}
          component={
            <button
              disabled={addressFormLoading}
              type={"submit"}
              className={cn(Styles.button, "form-button", "w-md-auto")}
            >
              {addressInfo ? "Save changes" : "Add Address"}
            </button>
          }
        />
        <div className={Styles.addAddressInputContainer}>
          <div>{children}</div>
        </div>
      </form>
    </div>
  );
};
