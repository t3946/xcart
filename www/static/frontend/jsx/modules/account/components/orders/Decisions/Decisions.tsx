import React from "react";
import appData from "@client/jsx/utils/AppData";
import List from "@client/modules/account/components/orders/Decisions/List";
const Decisions: React.FC = function () {
  const decisions = appData.decisions;

  return (
    <div className={"decisions-lists "}>
      <h2 className={"decisions-list-header decisions-lists__header mt-md-0"}>
        Order decisions required
      </h2>

      <List
        decisions={decisions.notResolved}
        className={"decisions-lists__required-list"}
      />

      <h2 className={"decisions-list-header decisions-lists__header"}>
        Order decisions made
      </h2>

      <List decisions={decisions.resolved} />
    </div>
  );
};

export default Decisions;
