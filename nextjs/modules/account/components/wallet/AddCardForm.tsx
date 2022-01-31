import React, { useContext } from "react";
import { Form, Formik, FormikHelpers } from "formik";
import { WalletCardsDialogContext } from "@modules/account/contexts/WalletCardsDialogContext";
import { BillingAddressFormEnum } from "@modules/account/ts/consts/billing-address-form-types";
import {
  addCardFormValidationSchema,
  initialAddCardFormValue,
} from "@modules/account/ts/consts/add-card-form";
import { useDispatch } from "react-redux";
import { addDataFromSubmitCardForm } from "@redux/actions/account-actions/PaymentsActions";
import { detectCardType } from "@modules/account/utils/detect-card-type";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StripeField from "@modules/ui/StripeField";
import loadStripe from "@utils/loadStripe";
import { Elements } from "@stripe/react-stripe-js";
import * as stripeJs from "@stripe/stripe-js";
import { useStripe, useElements } from "@stripe/react-stripe-js";
import Button from "@modules/ui/forms/Button";

export const AddCardForm: React.FC = () => {
  const stripe = useStripe();
  const elements = useElements();

  const context = useContext(WalletCardsDialogContext);
  const router = useRouter();
  const dispatch = useDispatch();
  const breakPoint = useBreakpoint();

  function cardsHandleCancel() {
    breakPoint({
      sm: () => {
        router.push("/account/payments/wallet");
      },
      md: context.handleClose,
    });
  }

  async function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    if (!stripe || !elements) {
      actions.setSubmitting(false);
      return;
    }

    const card: stripeJs.Card = elements.getElement("card");

    if (!card) {
      actions.setSubmitting(false);
      return;
    }

    card.name = "VAVAVA QQQ";

    console.log({ card }, card.name);

    let cardToken;

    await stripe.createToken(card).then((result) => {
      console.log({
        result,
        data: {
          last4: result.token.card.last4,
          exp_month: result.token.card.exp_month,
          exp_year: result.token.card.exp_year,
          brand: result.token.card.brand,
        },
      });
      cardToken = result.token;
    });

    if (!cardToken) {
      actions.setSubmitting(false);
      return;
    }

    console.log(cardToken.id);
    actions.setSubmitting(false);
    return;

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
              <Button type={"submit"} disabled={isSubmitting}>
                submit
              </Button>
            </Form>
          );
        }}
      </Formik>
    </div>
  );
};

/**
 * Когда пользователь отправляет карту, на сервер идёт токен карты.
 * По работе с API Stripe
 * Сервер проверяет, есть ли пользователь в stripe системе.
 *    если есть, тогда создаёт проверяет есть ли карта в списке карт клиента страйпа
 *      если карта есть -- ничего не делать
 *      если нет -- создать карту
 *    если нет -- создать пользователя, создать карту
 * По работе с серверной бд
 * Сохранить данные карты в бд
 */

/**
 * xcart_users
 *  stripe_customer_token
 *
 * account_credit_cards
 *  stripe_customer_token
 *  stripe_card_token
 *  address_id
 */
