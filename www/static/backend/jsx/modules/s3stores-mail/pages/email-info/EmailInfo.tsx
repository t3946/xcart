import React from "react";
import { useParams } from "react-router-dom";
import { useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { EmailInfoHeader } from "@s3stores-mail/components/ordinary/email-info-header/EmailInfoHeader";
import { EmailInfoContainer } from "@s3stores-mail/containers/email-info/EmailInfo.container";

export const EmailInfo: React.FC = () => {
  const { id }: { id: string } = useParams();

  const emailInfo = useSelector((state: StoreDto) => {
    return state.items.filter((e) => e.item.id === Number(id))[0];
  });

  return (
    <div>
      <EmailInfoHeader info={emailInfo.item} />
      <EmailInfoContainer />
    </div>
  );
};
