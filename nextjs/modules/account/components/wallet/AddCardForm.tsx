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
import Button from "@modules/ui/forms/Button";
import { addCardSaga } from "@redux/actions/account-actions/PaymentsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";
import { BillingAddressList } from "./BillingAddressList";

export const AddCardForm: React.FC = () => {
  const stripe = useStripe();
  const elements = useElements();
  const addresses = useSelectorAccount((e) =>
    e.addresses.addressesList?.filter(
      (address) => address.address_type === AddressTypeEnum.BILLING
    )
  );
  const userId = useSelectorAccount((e) => {
    return e.user?.user_id;
  });

  const context = useContext(WalletCardsDialogContext);
  const router = useRouter();
  const dispatch = useDispatch();
  const breakPoint = useBreakpoint();

  React.useEffect(() => {
    dispatch(getAddresses(userId));
  }, []);

  function cardsHandleCancel() {
    breakPoint({
      sm: () => {
        router.push("/account/payments/wallet");
      },
      md: context.handleClose,
    });
  }

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
      console.log(1);
      return;
    }

    dispatch(
      addCardSaga({
        data: {
          token: cardToken.id,
        },
        success: function () {
          console.log("success");
          window.location.reload();
        },
      })
    );
  }

  function onCardReady(cardElement: stripeJs.StripeCardElement) {}

  return (
    <div className="billing-address-container add-card-form-container">
      <Formik
        initialValues={initialAddCardFormValue}
        onSubmit={submit}
        // validationSchema={addCardFormValidationSchema}
      >
        {({
          errors,
          setFieldValue,
          values,
          touched,
          handleChange,
          handleBlur,
          isSubmitting,
        }) => {
          return (
            <Form className="your-order-form" encType="multipart/form-data">
              <StripeField onReady={onCardReady} />
              {addresses && (
                <>
                  <div className="dialog-title mt-4">
                    Select a billing address
                  </div>
                  <BillingAddressList
                    value={values.address}
                    onChange={handleChange}
                    addresses={addresses}
                    disabled={isSubmitting}
                  />
                </>
              )}
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
