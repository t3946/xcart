import React, { useContext } from "react";
import { Form, Formik } from "formik";
import { FormSelect } from "../shared/FormSelect";
import { FormInput } from "../shared/FormInput";
import { Button, Grid } from "@material-ui/core";
import { FormCheckBox } from "../shared/FormCheckBox";
import { fillMassToSelect } from "../../utils/fill-mass-to-select";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import {
  addCardFormValidationSchema,
  initialAddCardFormValue,
} from "../../ts/consts/add-card-form";

export const AddCardForm = () => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  const context = useContext(WalletCardsDialogContext);

  const handleSubmit = (values) => {
    context.setContent(BillingAddressFormEnum.LIST_ADDRESS);
  };

  return (
    <Grid xs={7} className="billing-address-container">
      <Formik
        initialValues={initialAddCardFormValue}
        onSubmit={handleSubmit}
        validationSchema={addCardFormValidationSchema}
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
                placeholder={"5026 2457 5478 5984"}
                value={values.cardNumber}
                name={"cardNumber"}
                errorMessage={errors.cardNumber}
                handleChange={handleChange}
                touched={touched.cardNumber}
                classes={{ input: "add-card-input" }}
                handleBlur={handleBlur}
              />
              <FormInput
                label={"Name on card"}
                value={values.name}
                name={"name"}
                errorMessage={errors.name}
                handleChange={handleChange}
                touched={touched.name}
                classes={{ input: "add-card-input" }}
                handleBlur={handleBlur}
              />
              <Grid container justify="space-between">
                <FormSelect
                  items={monthsValues}
                  value={values.expiration_month}
                  classes={{ group: "add-card-select-expiration-month" }}
                  label={"Expiration date"}
                  onClick={(value) => setFieldValue("expiration_month", value)}
                  name={"expiration_month"}
                />
                <FormSelect
                  items={yearsValues}
                  classes={{ group: "add-card-select-expiration-years" }}
                  value={values.expiration_year}
                  onClick={(value) => setFieldValue("expiration_year", value)}
                  name={"expiration_year"}
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
                    onClick={() => context.handleClose()}
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
