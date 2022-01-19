import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decision from "@modules/account/components/orders/Decision/Decision";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  const decisions: { solved: []; notSolved: [] } = {
    solved: [],
    notSolved: [],
  };

  await instance
    .get("/order/api/decisions/get", {
      data: {
        solved: 0,
        offset: 0,
      },
    })
    .then((res) => {
      decisions.notSolved = res.data;
    });

  await instance
    .get("/order/api/decisions/get", {
      data: {
        solved: 1,
        offset: 0,
      },
    })
    .then((res) => {
      decisions.solved = res.data;
    });

  return {
    props: {
      decisions,
      decisionId: parseInt(ctx.query.decisionId),
    },
  };
}

interface IProps {
  decisions: any;
  decisionId: number;
}

function DecisionPage(props: IProps) {
  const { decisionId, decisions } = props;

  if (!decisions) {
    return null;
  }

  const { solved, notSolved } = decisions;
  const decisionItems: Record<any, any>[] = [...solved, ...notSolved];
  const max = decisionItems.length;
  let decision;
  let i = 0;

  while (!decision && i < max) {
    if (decisionItems[i].decision_id === decisionId) {
      decision = decisionItems[i];
    }

    i++;
  }

  return (
    <PageTwoColumns>
      <Decision decision={decision} />
    </PageTwoColumns>
  );
}

export default DecisionPage;
