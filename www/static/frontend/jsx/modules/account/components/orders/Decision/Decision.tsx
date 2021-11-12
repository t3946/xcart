import React from "react";
import Navigation from "@client/modules/account/components/orders/Navigation/Navigation";
import EstimatedTimeArrival from "@client/modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival";
import AppData, { route } from "@client/jsx/utils/AppData";
import { useHistory } from "react-router-dom";

function getDecision() {
  const decisionId = parseInt(document.location.href.split("/").pop());
  const { resolved, notResolved } = AppData["decisions"];
  const decisions = [...resolved, ...notResolved];

  let decision;
  let i = 0;
  const max = decisions.length;

  while (!decision && i < max) {
    if (decisions[i].decision_id === decisionId) {
      decision = decisions[i];
    }

    i++;
  }

  return decision;
}

const Decision: React.FC = () => {
  const decision = getDecision();

  if (!decision) {
    const history = useHistory();
    history.push(route("account:order-decisions-required"));
  }

  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # {decision.options.order_number}
      </h1>
      <Navigation />
      <EstimatedTimeArrival />
    </div>
  );
};

export default Decision;
