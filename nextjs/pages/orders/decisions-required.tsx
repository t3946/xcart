import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decisions from "@modules/account/components/orders/DecisionsPreview/Decisions";
import { getInstance } from "@services/axios/Instance";
import { setDecisionsAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let decisions: { solved: []; notSolved: [] };

  await instance
    .get("/api-client/decisions/get-initial-state", {
      data: { skip: 0, take: 5 },
    })
    .then((res) => {
      decisions = res.data;
    });

  return {
    props: {
      decisions,
    },
  };
}

function DecisionsRequired(props: Record<any, any>) {
  const dispatch = useDispatch();

  if (!props.decisions) {
    return null;
  }

  dispatch(setDecisionsAction(props.decisions));

  return (
    <PageTwoColumns>
      <Decisions />
    </PageTwoColumns>
  );
}

export default DecisionsRequired;
