import React from "react";
import { FraudOrderContext } from "@admin/modules/order-fraud/ts/types/context";

export const FraudCheckOrderContext: React.Context<FraudOrderContext> =
  React.createContext(null);
