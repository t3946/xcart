import React, { useContext } from "react";
import { Form, Formik, FormikHelpers } from "formik";
import { WalletCardsDialogContext } from "@modules/account/contexts/WalletCardsDialogContext";
import { initialAddCardFormValue } from "@modules/account/ts/consts/add-card-form";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StripeField from "@modules/ui/StripeField";
import * as stripeJs from "@stripe/stripe-js";
import { useStripe, useElements } from "@stripe/react-stripe-js";
import Button from "@modules/ui/forms/Button";
import { addCardSaga } from "@redux/actions/account-actions/PaymentsActions";

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
