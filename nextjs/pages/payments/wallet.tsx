import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { Wallet } from "@components/pages/wallet/Wallet";
import { getInstance } from "@services/axios/Instance";
import loadStripe from "@utils/loadStripe";
const stripePromise = loadStripe();
import { Elements } from "@stripe/react-stripe-js";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let cards;
  let defaultCardId;

  await instance
    .get("/api-client/user/stripe/card/get")
    .then((res) => {
      cards = res.data.data;
    })
    .catch(() => {
      cards = [];
    });

  await instance
    .get("/api-client/user/stripe/customer/get")
    .then((res) => {
      defaultCardId = res.data.default_source;
    })
    .catch(() => {
      defaultCardId = null;
    });

  return {
    props: {
      cards,
      defaultCardId,
    },
  };
}

function WalletPage(props: Record<any, any>) {
  return (
    <PageTwoColumns>
      <Elements stripe={stripePromise}>
        <Wallet {...props} />
      </Elements>
    </PageTwoColumns>
  );
}

export default WalletPage;
