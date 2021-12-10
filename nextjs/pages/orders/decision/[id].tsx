import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decision from "@modules/account/components/orders/Decision/Decision";
import { useRouter } from "next/router";
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
    },
  };
}

function DecisionPage(props) {
  const router = useRouter();
  const decisionId: number = parseInt(router.query.id);
  const { solved, notSolved } = props.decisions;
  const decisions: [] = [...solved, ...notSolved];
  const max = decisions.length;
  let decision;
  let i = 0;

  while (!decision && i < max) {
    console.log(decisions[i].decision_id, decisionId);
    if (decisions[i].decision_id === decisionId) {
      decision = decisions[i];
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
