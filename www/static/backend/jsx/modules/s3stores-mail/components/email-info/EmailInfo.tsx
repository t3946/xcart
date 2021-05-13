import React from "react";
import { Redirect, useParams } from "react-router-dom";
import { useSelector } from "react-redux";
import { EmailInfoHeader } from "../email-info-header/EmailInfoHeader";
import { EmailInfoData } from "../email-info-data/EmailInfoData";

export const EmailInfo = () => {
  const { id }: any = useParams();

  const emailInfo = useSelector((state: any) => {
    return state.items.filter((e) => e.id === Number(id))[0];
  });

  if (!emailInfo) return <Redirect to="/admin/forms/email-dashboard" />;

  return (
    <div>
      <EmailInfoHeader info={emailInfo} />
      <EmailInfoData data={emailInfo} />
    </div>
  );
};
