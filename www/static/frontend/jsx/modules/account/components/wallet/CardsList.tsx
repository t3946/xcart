import React from "react";
import { CardItem } from "./CardItem";
import { useSelector } from "react-redux";
import { LoadingContainer } from "../shared/LoadingContainer";

export const CardsList = ({ cards }) => {
  const breakPoint = useSelector((e: any) => e.main.breakpoint);

  const submitCardFormLoading = useSelector(
    (e: any) => e.wallet.submitCardFormLoading
  );
  return (
    <div className="wallet-cards-list-container">
      {cards?.map((e, index) => {
        return (
          <LoadingContainer loading={submitCardFormLoading}>
            <CardItem
              breakPoint={breakPoint}
              cardInfo={e}
              firstChild={index === 0}
            />
          </LoadingContainer>
        );
      })}
    </div>
  );
};
