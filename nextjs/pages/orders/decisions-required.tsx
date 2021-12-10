import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import Decisions from "@modules/account/components/orders/DecisionsPreview/Decisions";
import { getInstance } from "@services/axios/Instance";
import { setDecisionsAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";

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

function DecisionsRequired(props: Record<any, any>) {
  const dispatch = useDispatch();

  dispatch(setDecisionsAction(props.decisions));

  return (
    <PageTwoColumns>
      <Decisions />
    </PageTwoColumns>
  );
}

export default DecisionsRequired;
