import React from "react";
import Navigation from "@modules/account/components/orders/Navigation/Navigation";
import LicenseRequire from "@modules/account/components/orders/Decision/LicenseRequire/LicenseRequire";
import PaymentRequired from "@modules/account/components/orders/Decision/PaymentRequired/PaymentRequired";
import UnpaidOrder from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder";
import SendingCheck from "@modules/account/components/orders/Decision/SendingCheck/SendingCheck";
import IncreaseInShippingCharge from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { useDispatch } from "react-redux";
import {
  addAction,
  resetAction,
} from "@redux/actions/account-actions/DecisionsActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

const Decision: React.FC = (props) => {
  const router = useRouter();
  const dispatch = useDispatch();
  const decision = props.decision;

  if (!decision) {
    if (process.browser) {
      router.push("/orders/decision-required");
    }
    return <span>no decision</span>;
  }

  function onChangeDecision(decision: DecisionsInterface) {
    dispatch(resetAction());
    dispatch(addAction(decision));
    router.push("/orders/decision-required");
  }

  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # {decision.order_number}
      </h1>
      <Navigation />
      {/*<EstimatedTimeArrival onChange={onChangeDecision} decision={decision} />*/}
      {/*<LicenseRequire onChange={onChangeDecision} decision={decision} />*/}
      {/* <PaymentRequired /> */}
      <UnpaidOrder onChangeDecision={onChangeDecision} decision={decision} />
      <SendingCheck
        firstAddress={{
          name: "S3 Stores, Inc.",
          address: `2885 Sanford Ave SW #12717
          Grandville, MI, 49418
          USA`,
        }}
        secondAddress={{
          name: "S3 Stores, Inc.",
          address: `27 Joseph St.,
          Chatham, Ontario, N7L 3G4
          Canada`,
        }}
      />
      <IncreaseInShippingCharge onChange={onChangeDecision} decision={decision} />
    </div>
  );
};

export default Decision;
