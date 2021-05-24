import React from "react";
import { useParams } from "react-router-dom";
import { useSelector } from "react-redux";
import { EmailInfoHeader } from "../email-info-header/EmailInfoHeader";
import { EmailInfoData } from "../email-info-data/EmailInfoData";
import { StoreDto } from "@s3stores-mail/ts/types";

export const EmailInfo: React.FC = () => {
  const { id }: { id: string } = useParams();

  const emailInfo = useSelector((state: StoreDto) => {
    return state.items.filter((e) => e.item.id === Number(id))[0];
  });

  return (
    <div>
      <EmailInfoHeader info={emailInfo.item} />
      <EmailInfoData />
    </div>
  );
};
