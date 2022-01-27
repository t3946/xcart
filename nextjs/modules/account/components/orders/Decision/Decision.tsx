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
import AlternativeItemsOffer from "@modules/account/components/orders/Decision/AlternativeItemsOffer/AlternativeItemsOffer";
import EstimatedTimeArrival from "@modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival";
import LTLFreightShipment from "@modules/account/components/orders/Decision/LTLFreightShipment/LTLFreightShipment";
import POAdditionalInformationRequired from "@modules/account/components/orders/Decision/POAdditionalInformationRequired/POAdditionalInformationRequired";
import StreetAddressRequired from "@modules/account/components/orders/Decision/StreetAddressRequired/StreetAddressRequired";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { useDispatch } from "react-redux";
import {
  addAction,
  resetAction,
} from "@redux/actions/account-actions/DecisionsActions";
import { useRouter } from "next/router";

interface IProps {
  decision: Record<any, any>;
}

const Decision: React.FC<IProps> = (props) => {
  const router = useRouter();
  const dispatch = useDispatch();
  const { decision } = props;

  async function onChangeDecision(decision: DecisionsInterface) {
    dispatch(resetAction());
    dispatch(addAction(decision));
    await router.push("/orders/decisions-required");
  }

  const components: Record<string, React.FC<any>> = {
    "estimated-time-arrival": EstimatedTimeArrival,
    "ach-payment-required": AchPaymentIsRequired,
    "license-required": LicenseRequire,
    "unpaid-order": UnpaidOrder,
    "send-us-po": OriginalPurchaseOrder,
    "increase-shipping-charge": IncreaseInShippingCharge,
    "send-check": SendingCheck,
    "street-address-required": StreetAddressRequired,
    "questions-ltl-freight-shipment": LTLFreightShipment,
    "responsibility-for-custom-duties": CustomDuties,
    "alternative-items-offer": AlternativeItemsOffer,
    "additional-shipping-charge": AdditionalShippingCharge,
    "additional-information-required": POAdditionalInformationRequired,
  };
  const DecisionComponents: React.FC<any> = components[decision.type];

  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # {decision.order_number}
      </h1>
      <Navigation orderId={decision.order_id} />
      <DecisionComponents onChange={onChangeDecision} decision={decision} />
    </div>
  );
};

export default Decision;
