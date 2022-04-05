import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decision from "@modules/account/components/orders/Decision/Decision";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  const decisionId = parseInt(ctx.query.id);
  let decision = null;
  let paypalUrl = null;
  let cards;
  let defaultCardId;

  await instance
    .post("/api-client/user/decisions/get", {
      decisionId,
    })
    .then((res: any) => {
      decision = res.data.decision;

      if (decision.type.slug === "unpaid-order") {
        paypalUrl = res.data.paypalUrl;
      }
    });

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
      decision,
      cards,
      defaultCardId,
      paypalUrl,
    },
  };
}

interface IProps {
  decision: Record<any, any>;
  decisionId: number;
  cards: any;
  defaultCardId: any;
}

function DecisionPage(props: IProps) {
  const { decision, cards, defaultCardId, paypalUrl } = props;

  return (
    <PageTwoColumns>
      <Decision
        decision={decision}
        paypalUrl={paypalUrl}
        cards={cards}
        defaultCardId={defaultCardId}
      />
    </PageTwoColumns>
  );
}

export default DecisionPage;
