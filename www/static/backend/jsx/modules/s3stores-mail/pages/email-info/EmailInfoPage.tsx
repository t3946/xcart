import React from "react";
import { EmailInfoContainer } from "@s3stores-mail/containers";
import { useScrollToUp } from "@s3stores-mail/hooks/useScrollToUp";

export const EmailInfoPage: React.FC = () => {
  useScrollToUp();
  return <EmailInfoContainer />;
};
