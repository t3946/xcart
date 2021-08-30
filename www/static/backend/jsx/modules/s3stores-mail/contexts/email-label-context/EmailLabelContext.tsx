import React from "react";
import { LabelContext } from "@s3stores-mail/ts/types/label";

export const EmailLabelContext: React.Context<LabelContext> =
  React.createContext(null);
