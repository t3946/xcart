import React from "react";
import List from "@client/modules/account/components/orders/DecisionsPreview/List";
import { Row } from "react-bootstrap";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";
import classnames from "classnames";

const Decisions: React.FC = function () {
  const [isPrintSolved, setIsPrintSolved] = React.useState(true);
  const [isPrintNotSolved, setIsPrintNotSolved] = React.useState(true);
  const decisions = useSelectorAccount((state) => state.decisions);

  const lists = [];

  if (isPrintNotSolved) {
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
            onAllLoaded={() => {
              if (decisions.notSolved.decisions.length === 0) {
                setIsPrintNotSolved(false);
              }
            }}
            key={"notSolved"}
          />
        </Row>
      </>
    );
  }

  const classes = {
    header: [
      "decisions-list-header",
      "decisions-lists__header",
      {
        "mt-0": !isPrintNotSolved,
      },
    ],
  };

  if (isPrintSolved) {
    lists.push(
      <>
        <h2 className={classnames(classes.header)}>Order decisions made</h2>

        <Row className={"m-sm-0"}>
          <List
            solved={true}
            decisions={decisions.solved.decisions}
            className={"px-0 common-scrollbar"}
            onAllLoaded={() => {
              if (decisions.solved.decisions.length === 0) {
                setIsPrintSolved(false);
              }
            }}
            key={"solved"}
          />
        </Row>
      </>
    );
  }

  return <div className={"decisions-lists"}>{lists}</div>;
};

export default Decisions;
