import React, { useContext } from "react";
import { Form, Formik, FormikHelpers } from "formik";
import { WalletCardsDialogContext } from "@modules/account/contexts/WalletCardsDialogContext";
import { initialAddCardFormValue } from "@modules/account/ts/consts/add-card-form";
import { useDispatch } from "react-redux";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StripeField from "@modules/ui/StripeField";
import * as stripeJs from "@stripe/stripe-js";
import { useStripe, useElements } from "@stripe/react-stripe-js";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { addCardSaga } from "@redux/actions/account-actions/PaymentsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import { BillingAddressList } from "./BillingAddressList";
import { addCardFormValidationSchema } from "@modules/account/ts/consts/add-card-form";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import FormGroup from "@modules/ui/forms/FormGroup";
import AddBillingAddressForm from "./AddBillingAddressForm";
import cn from "classnames";

export const AddCardForm: React.FC = () => {
  const stripe = useStripe();
  const elements = useElements();
  const user = useSelectorAccount((e) => e.user);
  const addresses = useSelectorAccount((e) =>
    e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    )
  );

  const [addAddressForm, setAddAddressForm] = React.useState(false);

  const dispatch = useDispatch();

  React.useEffect(() => {
    dispatch(getAddresses(user.user_id));
  }, []);

  async function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    if (!elements || !stripe) {
      return;
    }

    const card: stripeJs.StripeCardElement | null = elements.getElement("card");

    if (!card) {
      return;
    }

    actions.setSubmitting(true);

    let cardToken: stripeJs.Token | undefined;

    await stripe.createToken(card).then((result: stripeJs.TokenResult) => {
      cardToken = result.token;
      result.error && alert(result.error?.message);
    });

    if (!cardToken) {
      actions.setSubmitting(false);
      return;
    }
    const data = {
      token: cardToken.id,
      addressId: parseInt(values.address),
      cardHolderName: values.cardHolderName,
    };

    dispatch(
      addCardSaga({
        data,
        success: function () {
          window.location.reload();
        },
      })
    );
  }

  function onCardReady(cardElement: stripeJs.StripeCardElement) {}

  return (
    <div
      className={cn("billing-address-container", {
        "add-card-form-container": !addAddressForm,
      })}
    >
      <Formik
        initialValues={initialAddCardFormValue}
        onSubmit={submit}
        validationSchema={addCardFormValidationSchema}
      >
        {({
          errors,
          setFieldValue,
          values,
          touched,
          handleChange,
          handleBlur,
          isSubmitting,
          setErrors,
        }) => {
          if (addAddressForm) {
            return (
              <AddBillingAddressForm
                onSubmitted={(address) => {
                  setFieldValue("address", address.address_id.toString());
                  setAddAddressForm(false);
                }}
                onCancel={() => setAddAddressForm(false)}
              />
            );
          }
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <FormGroup
                label="Cardholder name"
                input={
                  <Input
                    value={values.cardHolderName}
                    name="cardHolderName"
                    onChange={handleChange}
                    isValid={!!touched.cardHolderName && !errors.cardHolderName}
                    isInvalid={
                      !!touched.cardHolderName && !!errors.cardHolderName
                    }
                  />
                }
                error={!!touched.cardHolderName && errors.cardHolderName}
              />

              <FormGroup
                label="Card"
                input={
                  <StripeField
                    error={values.cardNumber}
                    setError={(e: string) => {
                      setErrors({ cardNumber: e });
                    }}
                    onReady={onCardReady}
                  />
                }
                error={!!touched.cardNumber && errors.cardNumber}
              />

              {addresses && (
                <>
                  <div className="dialog-title mt-4">
                    Select a billing address
                    <Feedback
                      className={"d-block position-absolute"}
                      type="invalid"
                    >
                      {!!touched.address && errors.address}
                    </Feedback>
                  </div>

                  <BillingAddressList
                    value={values.address}
                    onChange={handleChange}
                    addresses={addresses}
                    disabled={isSubmitting}
                    classes={{ container: "mb-1" }}
                  />
                </>
              )}
              <Button
                theme={ETheme.outlined}
                className={"mb-5 fs-6 w-auto px-4"}
                type="button"
                onClick={() => setAddAddressForm(true)}
              >
                Add billing address
              </Button>
              <Button
                type={"submit"}
                disabled={isSubmitting || !stripe || !elements}
              >
                submit
              </Button>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

export default AddCardForm;
