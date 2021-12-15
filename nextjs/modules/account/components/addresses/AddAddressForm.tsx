import React, { useContext } from "react";
import { FormSelect } from "../shared/FormSelect";
import { FormCheckBox } from "../shared/FormCheckBox";
import { Form, Formik, useFormik } from "formik";
import FormInputPhone, {
  getPhoneNumberInnerPart,
} from "@modules/account/components/shared/FormInputPhone";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "../../ts/consts/add-address-form";
import { useDispatch, useSelector } from "react-redux";
import {
  addAddress,
  editAddress,
} from "../../../../redux/actions/account-actions/AddressActions";
import { useRouter } from "next/router";
import { getStates } from "../../utils/get-states";
import Store from "@redux/stores/Store";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/AddAddressForm.module.scss";
import { getCountryByCode } from "@utils/Countries";

export const AddAddressForm: React.FC<any> = ({
  addressInfo = undefined,
  onCancelClick = undefined,
  children,
}) => {
  const dispatch = useDispatch();
  const history = useRouter();
  const countries = useSelector((e: any) => e.main.countries);
  const states = useSelector((e: any) => e.main.states);
  const { showSnackbar } = useContext(SnackbarContext);
  const breakpoint = useBreakpoint();

  const addressFormLoading = useSelector(
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

    dispatch(addAddress(newAddress, onPended, Store.getState().user.id));
  };
  if (addressInfo) {
    getCountryByCode();
  }
  console.log(addressInfo || initialAddAddressFormValue);

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
        <FormSelect
          items={countries}
          value={formik.values.country}
          label={"Country"}
          classes={{ input: "add-address-input", group: "mb-20" }}
          onClick={(value) => {
            formik.setFieldValue("country", value);
            formik.setFieldValue("state", initialAddAddressFormValue.state);
            formik.setFieldValue("country", value);
          }}
          name={"state"}
          id={"add-address-country"}
          errorMessage={
            formik.touched.country?.value && formik.errors.country?.value
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
          label={"Phone Number"}
        />

        <InputGroup
          label="Address"
          value={formik.values.street}
          name={"street"}
          error={formik.touched.street && formik.errors.street}
          isInvalid={!!(formik.touched.street && formik.errors.street)}
          handleChange={formik.handleChange}
        />

        <InputGroup
          placeholder="Apt, suite, unit, building, floor, etc."
          value={formik.values.detailed}
          name={"detailed"}
          error={formik.touched.detailed && formik.errors.detailed}
          isInvalid={!!(formik.touched.detailed && formik.errors.detailed)}
          handleChange={formik.handleChange}
          touched={formik.touched.detailed}
          classes={{ input: "add-address-input", grid: "justify-content-end" }}
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
        <FormSelect
          classes={{ input: "add-address-input", group: "mb-20" }}
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

        <InputGroup
          label={"Zip/Postal Code"}
          placeholder="39213"
          value={formik.values.zip}
          name={"zip"}
          error={formik.touched.zip && formik.errors.zip}
          isInvalid={!!(formik.touched.zip && formik.errors.zip)}
          handleChange={formik.handleChange}
        />
        <div className={Styles.addAddressCheckbox}>
          <div className="add-address-input">
            <FormCheckBox
              label={"Make this my default address"}
              value={formik.values.is_default}
              name={"is_default"}
              handleChange={formik.handleChange}
            />
          </div>
        </div>
        <div className={Styles.addAddressInputContainer}>
          <div className="add-address-input">
            <button
              disabled={addressFormLoading}
              type={"submit"}
              className={cn("account-submit-btn", "w-md-auto")}
            >
              {addressInfo ? "Save changes" : "Add Address"}
            </button>
            {children}
          </div>
        </div>
      </form>
    </div>
  );
};
