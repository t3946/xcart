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
import { accountStore } from "../../../../redux/stores/StoreAccount";
import { SnackbarContext } from "@client/modules/account/contexts/snackbar/Snackbar.context";

export const AddAddressForm = ({ addressInfo }) => {
  const dispatch = useDispatch();
  const history = useHistory();
  const countries = useSelector((e: any) => e.main.countries);
  const states = useSelector((e: any) => e.main.states);
  const { showSnackbar } = useContext(SnackbarContext);

  const addressFormLoading = useSelector(
    (e: any) => e.addresses.addressFormLoading
  );

  const onPended = () => {
    history.push("/account/addresses");
    showSnackbar({
      header: "Success",
      message: `${!addressInfo ? "Address added!" : "Address edit!"}`,
      theme: "success",
    });
  };

  const submitForm = (values) => {
    const newAddress = {
      ...values,
      country: values.country.value,
      state: values.state.value,
    };

    if (addressInfo) {
      dispatch(editAddress(newAddress, onPended));
      return;
    }

    dispatch(addAddress(newAddress, onPended, accountStore.getState().user.id));
  };
  return (
    <div className="add-address-form-container">
      <Formik
        initialValues={addressInfo || initialAddAddressFormValue}
        onSubmit={submitForm}
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
                classes={{ input: "add-address-input", group: "mb-20" }}
                onClick={(value) => {
                  setFieldValue("country", value);
                  setFieldValue("state", initialAddAddressFormValue.state);
                }}
                name={"state"}
                id={"add-address-country"}
              />
              <FormInput
                label={"Full Name (First and Last name)"}
                placeholder={"Albert H. Einstein"}
                value={values.full_name}
                name={"full_name"}
                errorMessage={errors.full_name}
                touched={touched.full_name}
                classes={{ input: "add-address-input" }}
                handleBlur={handleBlur}
                handleChange={handleChange}
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
                classes={{ input: "add-address-input", group: "mb-20" }}
                items={getStates(states, values.country.value)}
                value={values.state}
                label={"State/Province"}
                onClick={(value) => setFieldValue("state", value)}
                name={"state"}
                id={"add-address-state"}
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
              <Grid
                className="add-address-checkbox"
                container
                justifyContent="flex-end"
              >
                <Grid className="add-address-input">
                  <FormCheckBox
                    label={"Make this my default address"}
                    value={values.is_default}
                    name={"is_default"}
                    handleChange={handleChange}
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
                </Grid>
              </Grid>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
