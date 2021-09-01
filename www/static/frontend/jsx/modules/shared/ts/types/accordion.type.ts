import * as React from "react";

export interface AccordionPropsDto {
  children: React.ReactNode;
  text: string;
  lastChild?: boolean;
}
