import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { Transactions } from "@modules/account/pages/Transactions";
import { getInstance } from "@services/axios/Instance";
import { AxiosResponse } from "axios";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let orders, cards;

  await instance
    .get("/api-client/user/get-transactions")
    .then((res: AxiosResponse) => {
      orders = res.data.orders;
      cards = res.data.cards;
    });

  return {
    props: { orders, cards },
  };
}

function TransactionsPage(props: Record<any, any>) {
  return (
    <PageTwoColumns>
      <Transactions {...props} />
    </PageTwoColumns>
  );
}

export default TransactionsPage;
