import React from "react";
import Navigation from "@modules/account/components/orders/Navigation/Navigation";
import LicenseRequire from "@modules/account/components/orders/Decision/LicenseRequire/LicenseRequire";
import OriginalPurchaseOrder from "@modules/account/components/orders/Decision/OriginalPurchaseOrder/OriginalPurchaseOrder";
import UnpaidOrder from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder";
import SendingCheck from "@modules/account/components/orders/Decision/SendingCheck/SendingCheck";
import IncreaseInShippingCharge from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge";
import AchPaymentIsRequired from "@modules/account/components/orders/Decision/AchPaymentIsRequired/AchPaymentIsRequired";
import AdditionalShippingCharge from "@modules/account/components/orders/Decision/AdditionalShippingCharge/AdditionalShippingCharge";
import CustomDuties from "@modules/account/components/orders/Decision/CustomDuties/CustomDuties";
import PaymentRequired from "@modules/account/components/orders/Decision/PaymentRequired/PaymentRequired";
import AlternativeItemsOffer from "@modules/account/components/orders/Decision/AlternativeItemsOffer/AlternativeItemsOffer";
import EstimatedTimeArrival from "@modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival";
import LTLFreightShipment from "@modules/account/components/orders/Decision/LTLFreightShipment/LTLFreightShipment";
import POAdditionalInformationRequired from "@modules/account/components/orders/Decision/POAdditionalInformationRequired/POAdditionalInformationRequired"
import DecisionsInterface from "@modules/account/ts/types/decision";
import { useDispatch } from "react-redux";
import {
  addAction,
  resetAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useRouter } from "next/router";

const Decision: React.FC = (props) => {
  const router = useRouter();
  const dispatch = useDispatch();
  const decision = props.decision;

  if (!decision) {
    if (process.browser) {
      router.push("/orders/decisions-required");
    }
    return <span>no decision</span>;
  }

  function onChangeDecision(decision: DecisionsInterface) {
    dispatch(resetAction());
    dispatch(addAction(decision));
    router.push("/orders/decisions-required");
  }

  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # {decision.order_number}
      </h1>
      <Navigation />
      {(() => {
        const props = {
          onChange: onChangeDecision,
          decision,
        };

        switch (decision.type) {
          case "eta":
            return <EstimatedTimeArrival {...props} />;
          case "license":
            return <LicenseRequire {...props} />;
          case "payment":
            return <UnpaidOrder {...props} />;
        }
      })()}

      {/*<UnpaidOrder onChangeDecision={onChangeDecision} decision={decision} />*/}
      {/*<SendingCheck*/}
      {/*  firstAddress={{*/}
      {/*    name: "S3 Stores, Inc.",*/}
      {/*    address: `2885 Sanford Ave SW #12717*/}
      {/*    Grandville, MI, 49418*/}
      {/*    USA`,*/}
      {/*  }}*/}
      {/*  secondAddress={{*/}
      {/*    name: "S3 Stores, Inc.",*/}
      {/*    address: `27 Joseph St.,*/}
      {/*    Chatham, Ontario, N7L 3G4*/}
      {/*    Canada`,*/}
      {/*  }}*/}
      {/*/>*/}
      {/*<IncreaseInShippingCharge onChange={onChangeDecision} decision={decision} />*/}
      {/*<OriginalPurchaseOrder onChange={onChangeDecision} decision={decision} />*/}
      {/*<AchPaymentIsRequired />*/}
      {/* <CustomDuties /> */}
      {/* <AdditionalShippingCharge /> */}
      {/* <POAdditionalShippingCharge /> */}
      <POAdditionalInformationRequired />
    </div>
  );
};

export default Decision;
