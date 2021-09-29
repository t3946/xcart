import React from "react";

export interface MobileMenuForListItem {
  component?: React.ReactNode;
  label?: string | React.ReactNode;
  image?: string;
  onClick?: () => void;
}
