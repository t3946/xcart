import React from "react";
import Navigation from "@client/modules/account/components/orders/Navigation/Navigation";
import LicenseRequire from "@client/modules/account/components/orders/Decision/LicenseRequire/LicenseRequire";
import { route } from "@client/jsx/utils/AppData";
import { useHistory } from "react-router-dom";
import DecisionsInterface from "@client/modules/account/ts/types/decision";
import { useDispatch } from "react-redux";
import {
  addAction,
  resetAction,
} from "@client/jsx/redux/actions/account-actions/DecisionsActions";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

function getDecision() {
  const decisionId = parseInt(document.location.href.split("/").pop());
  const { solved, notSolved } = useSelectorAccount((e) => e.decisions);
  const decisions = [...solved.decisions, ...notSolved.decisions];

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
  const dispatch = useDispatch();
  const decision = getDecision();
  const history = useHistory();

  if (!decision) {
    history.push(route("account:order-decisions-required"));
    return;
  }

  function onChangeDecision(decision: DecisionsInterface) {
    dispatch(resetAction());
    dispatch(addAction(decision));
    history.push(route("account:order-decisions-required"));
  }

  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # {decision.order_number}
      </h1>
      <Navigation />
      {/*<EstimatedTimeArrival onChange={onChangeDecision} decision={decision} />*/}
      <LicenseRequire onChange={onChangeDecision} decision={decision} />
    </div>
  );
};

export default Decision;
