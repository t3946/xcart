import React, { useContext } from "react";
import { FormInput } from "../shared/FormInput";
import { FormSelect } from "../shared/FormSelect";
import { Button, Grid } from "@material-ui/core";
import { FormCheckBox } from "../shared/FormCheckBox";
import { Form, Formik, useFormik } from "formik";
import {
  initialAddAddressFormValue,
  addAddressFormValidationSchema,
} from "../../ts/consts/add-address-form";
import { useDispatch, useSelector } from "react-redux";
import {
  addAddress,
  editAddress,
} from "../../../../redux/actions/account-actions/AddressActions";
import { useHistory } from "react-router-dom";
import { getStates } from "../../utils/get-states";
import Store from "@redux/stores/Store";
import { SnackbarContext } from "@modules/account/contexts/snackbar/Snackbar.context";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

export const AddAddressForm: React.FC<any> = ({
  addressInfo = undefined,
  onCancelClick = undefined,
  children,
}) => {
  const dispatch = useDispatch();
  const history = useHistory();
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
      country: formik.values.country.value,
      state: formik.values.state.value,
    };

    if (addressInfo) {
      dispatch(editAddress(newAddress, onPended));
      return;
    }

    dispatch(addAddress(newAddress, onPended, Store.getState().user.id));
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
          errorMessage={formik.errors.country?.value}
        />
        <FormInput
          label={"Full Name (First and Last name)"}
          placeholder={"Albert H. Einstein"}
          value={formik.values.full_name}
          name={"full_name"}
          errorMessage={formik.errors.full_name}
          touched={formik.touched.full_name}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
          handleChange={formik.handleChange}
        />
        <FormInput
          label={"Phone Number"}
          value={formik.values.phone_number}
          name={"phone_number"}
          errorMessage={formik.errors.phone_number}
          handleChange={formik.handleChange}
          touched={formik.touched.phone_number}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
          mask={"+9 (999) 999 99 99"}
        />
        <FormInput
          placeholder="Street address or P.O. Box"
          label={"Address"}
          value={formik.values.street}
          name={"street"}
          errorMessage={formik.errors.street}
          handleChange={formik.handleChange}
          touched={formik.touched.street}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
        />
        <FormInput
          placeholder="Apt, suite, unit, building, floor, etc."
          value={formik.values.detailed}
          name={"detailed"}
          errorMessage={formik.errors.detailed}
          handleChange={formik.handleChange}
          touched={formik.touched.detailed}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
        />
        <FormInput
          label={"City"}
          placeholder="Jackson"
          value={formik.values.city}
          name={"city"}
          errorMessage={formik.errors.city}
          handleChange={formik.handleChange}
          touched={formik.touched.city}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
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
          errorMessage={formik.errors.state?.value}
        />
        <FormInput
          label={"Zip/Postal Code"}
          placeholder="39213"
          value={formik.values.zip}
          errorMessage={formik.errors.zip}
          name={"zip"}
          handleChange={formik.handleChange}
          touched={formik.touched.zip}
          classes={{ input: "add-address-input" }}
          handleBlur={formik.handleBlur}
        />
        <Grid
          className="add-address-checkbox"
          container
          justifyContent="flex-end"
        >
          <Grid className="add-address-input">
            <FormCheckBox
              label={"Make this my default address"}
              value={formik.values.is_default}
              name={"is_default"}
              handleChange={formik.handleChange}
            />
          </Grid>
        </Grid>
        <Grid container justifyContent="flex-end">
          <Grid className="add-address-input">
            <Button
              disabled={addressFormLoading}
              type={"submit"}
              className="account-submit-btn"
            >
              {addressInfo ? "Save changes" : "Add Address"}
            </Button>
            {children}
          </Grid>
        </Grid>
      </form>
    </div>
  );
};
