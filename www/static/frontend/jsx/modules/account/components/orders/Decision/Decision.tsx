import React from "react";
import Navigation from "@client/modules/account/components/orders/Navigation/Navigation";
import EstimatedTimeArrival from "@client/modules/account/components/orders/Decision/EstimatedTimeArrival/EstimatedTimeArrival";

const Decision: React.FC = () => {
  return (
    <div>
      <h1 className={"text-center fw-bold decision-header decision__header"}>
        Order # AR-283574
      </h1>
      <Navigation />
      <EstimatedTimeArrival />
    </div>
  );
};

export default Decision;
