import React from "react";
import { Form, Formik } from "formik";
import { FormSelect } from "../shared/FormSelect";
import {
  addAddressFormValidationSchema,
  initialAddAddressFormValue,
} from "../../ts/consts/add-address-form";
import { FormInput } from "../shared/FormInput";
import { Button, Grid } from "@material-ui/core";
import { FormCheckBox } from "../shared/FormCheckBox";
import { fillMassToSelect } from "../../utils/fill-mass-to-select";

export const AddCardForm = () => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  return (
    <Grid xs={7} className="add-address-form-container">
      <Formik
        initialValues={initialAddAddressFormValue}
        onSubmit={null}
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
              <FormInput
                label={"Card number"}
                placeholder={"Albert H. Einstein"}
                value={values.full_name}
                name={"full_name"}
                errorMessage={errors.full_name}
                handleChange={handleChange}
                touched={touched.full_name}
                classes={{ input: "add-card-input" }}
                handleBlur={handleBlur}
              />
              <FormInput
                label={"Name on card"}
                value={values.phone_number}
                name={"phone_number"}
                errorMessage={errors.phone_number}
                handleChange={handleChange}
                touched={touched.phone_number}
                classes={{ input: "add-card-input" }}
                handleBlur={handleBlur}
              />
              <Grid container justify="space-between">
                <FormSelect
                  items={monthsValues}
                  value={values.state}
                  classes={{ group: "add-card-select-expiration-month" }}
                  label={"Expiration date"}
                  onClick={(value) => setFieldValue("state", value)}
                  name={"state"}
                />
                <FormSelect
                  items={yearsValues}
                  classes={{ group: "add-card-select-expiration-years" }}
                  value={values.state}
                  onClick={(value) => setFieldValue("state", value)}
                  name={"state"}
                />
              </Grid>

              <Grid
                className="add-address-checkbox"
                container
                justify="flex-end"
              >
                <Grid xs={7}>
                  <FormCheckBox
                    label={"Make this my default address"}
                    value={values.is_default}
                    name={"is_default"}
                    handleChange={handleChange}
                  />
                </Grid>
              </Grid>
              <Grid container justify="flex-end">
                <Grid xs={7} container justify="space-between">
                  <Button
                    type={"submit"}
                    className="account-submit-btn account-submit-btn-outline auto-width-button"
                  >
                    Cancel
                  </Button>
                  <Button
                    type={"submit"}
                    className="account-submit-btn auto-width-button"
                  >
                    Add your card
                  </Button>
                </Grid>
              </Grid>
            </Form>
          );
        }}
      </Formik>
    </Grid>
  );
};
