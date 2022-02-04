import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { Wallet } from "@components/pages/wallet/Wallet";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let cards;
  let defaultCardId;

  await instance.get("/api-client/user/stripe/card/get").then((res) => {
    cards = res.data.data;
  });

  await instance.get("/api-client/user/stripe/customer/get").then((res) => {
    defaultCardId = res.data.default_source;
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
      <Wallet {...props} />
    </PageTwoColumns>
  );
}

export default WalletPage;
