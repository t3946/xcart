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
import { useDispatch } from "react-redux";
import { addDataFromSubmitCardForm } from "../../../../redux/actions/account-actions/WalletActions";

export const AddCardForm = () => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  const context = useContext(WalletCardsDialogContext);

  const dispatch = useDispatch();

  const handleSubmit = (values) => {
    context.setContent(BillingAddressFormEnum.LIST_ADDRESS);

    dispatch(
      addDataFromSubmitCardForm({
        card: {
          name: values.name,
          card_number: values.cardNumber,
          expires:
            values.expiration_month.value + "/" + values.expiration_year.value,
          is_default: values.is_default,
        },
      })
    );
  };

  return (
    <div className="billing-address-container add-card-form-container">
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
                mask={"9999 9999 9999 9999"}
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
              <Grid container justify="space-between" alignContent="center">
                <label className="form-input-label">Expiration date</label>
                <div className="expirations-date-container add-card-input">
                  <FormSelect
                    items={monthsValues}
                    value={values.expiration_month}
                    classes={{ group: "add-card-select-expiration" }}
                    onClick={(value) =>
                      setFieldValue("expiration_month", value)
                    }
                    name={"expiration_month"}
                    id={"select-expiration-month"}
                  />
                  <FormSelect
                    items={yearsValues}
                    classes={{ group: "add-card-select-expiration" }}
                    value={values.expiration_year}
                    onClick={(value) => setFieldValue("expiration_year", value)}
                    name={"expiration_year"}
                    id={"select-expirations-year"}
                  />
                </div>
              </Grid>

              <Grid
                className="add-address-checkbox"
                container
                justify="flex-end"
              >
                <div className="add-card-input">
                  <FormCheckBox
                    label={"Make this my default card"}
                    value={values.is_default}
                    name={"is_default"}
                    handleChange={handleChange}
                  />
                </div>
              </Grid>
              <Grid container justify="flex-end">
                <div className="add-card-form-btns">
                  <Button
                    onClick={() => context.handleClose()}
                    className="account-submit-btn account-submit-btn-outline auto-width-button cancel-btn"
                  >
                    Cancel
                  </Button>
                  <Button
                    type={"submit"}
                    className="account-submit-btn auto-width-button"
                  >
                    Add your card
                  </Button>
                </div>
              </Grid>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};
