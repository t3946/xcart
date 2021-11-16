import React from "react";
import List from "@client/modules/account/components/orders/DecisionsPreview/List";
import { Row } from "react-bootstrap";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

const Decisions: React.FC = function () {
  const decisions = useSelectorAccount((state) => state.decisions);

  const lists = [];

  if (decisions.notSolved.decisions.length) {
    lists.push(
      <>
        <h2 className={"decisions-list-header decisions-lists__header mt-md-0"}>
          Order decisions required
        </h2>

        <Row className={"m-sm-0"}>
          <List
            solved={false}
            decisions={decisions.notSolved.decisions}
            className={"decisions-lists__required-list px-0 common-scrollbar"}
          />
        </Row>
      </>
    );
  }

  if (decisions.solved.decisions.length) {
    lists.push(
      <>
        <h2 className={"decisions-list-header decisions-lists__header"}>
          Order decisions made
        </h2>

        <Row className={"m-sm-0"}>
          <List
            solved={true}
            decisions={decisions.solved.decisions}
            className={"px-0 common-scrollbar"}
          />
        </Row>
      </>
    );
  }

  return <div className={"decisions-lists"}>{lists}</div>;
};

export default Decisions;
