import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decision from "@modules/account/components/orders/Decision/Decision";
import {getInstance} from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  const decisionId = parseInt(ctx.query.id);
  let decision = null;

  await instance
    .post("/api-client/user/decisions/get", {
      decisionId,
    })
    .then((res: any) => {
      decision = res.data.decision;
    });

  return {
    props: {
      decision,
    },
  };
}

interface IProps {
  decision: Record<any, any>;
  decisionId: number;
}

function DecisionPage(props: IProps) {
  const { decision } = props;

  return (
    <PageTwoColumns>
      <Decision decision={decision} />
    </PageTwoColumns>
  );
}

export default DecisionPage;
