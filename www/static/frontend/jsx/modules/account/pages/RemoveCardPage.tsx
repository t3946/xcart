import React from "react";
import { RemoveCard } from "../components/wallet/RemoveCard";
import { useLocation } from "react-router-dom";

export const RemoveCardPage = () => {
  const location = useLocation<any>();
  return <RemoveCard cardInfo={location.state.cardInfo} />;
};
