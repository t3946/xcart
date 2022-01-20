import React, { useContext } from "react";
import { Form, Formik } from "formik";
import FormSelect from "@modules/ui/forms/Select";
import { FormInput } from "../shared/FormInput";
import { FormCheckBox } from "../shared/FormCheckBox";
import { fillMassToSelect } from "../../utils/fill-mass-to-select";
import { WalletCardsDialogContext } from "../../contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "../../ts/consts/billing-address-form-types";
import {
  addCardFormValidationSchema,
  initialAddCardFormValue,
} from "../../ts/consts/add-card-form";
import { useDispatch } from "react-redux";
import { addDataFromSubmitCardForm } from "../../../../redux/actions/account-actions/PaymentsActions";
import { detectCardType } from "../../utils/detect-card-type";
import Store from "@redux/stores/Store";
import { useRouter } from "react/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

export const AddCardForm: React.FC = () => {
  const monthsValues = fillMassToSelect(1, 12);

  const yearsValues = fillMassToSelect(
    new Date().getFullYear(),
    new Date().getFullYear() + 10
  );

  const context = useContext(WalletCardsDialogContext);

  const router = useRouter();

  const dispatch = useDispatch();

  const breakPoint = useBreakpoint();

  const handleSubmit = (values) => {
    context.setContent(BillingAddressFormEnum.LIST_ADDRESS);

    dispatch(
      addDataFromSubmitCardForm({
        card: {
          name: values.name,
          card_number: values.cardNumber,
          expires: Date.parse(
            new Date(
              values.expiration_year.value,
              values.expiration_month.value
            ).toString()
          ),
          is_default: values.is_default,
          card_type: detectCardType(values.cardNumber),
        },
      })
    );
  };

  const cardsHandleCancel = () => {
    breakPoint({
      sm: () => {
        router.push("/account/payments/wallet");
      },
      md: context.handleClose,
    });
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
              <div className="d-flex justify-content-center align-center">
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
              </div>

              <div className="d-flex justify-content-end add-address-checkbox">
                <div className="add-card-input">
                  <FormCheckBox
                    label={"Make this my default card"}
                    value={values.is_default}
                    name={"is_default"}
                    handleChange={handleChange}
                  />
                </div>
              </div>
              <div className="d-flex justify-content-end">
                <div className="add-card-form-btns">
                  <button
                    onClick={cardsHandleCancel}
                    className="form-button account-submit-btn account-submit-btn-outline auto-width-button cancel-btn"
                  >
                    Cancel
                  </button>
                  <button
                    type={"submit"}
                    className="form-button account-submit-btn auto-width-button"
                  >
                    Add your card
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
