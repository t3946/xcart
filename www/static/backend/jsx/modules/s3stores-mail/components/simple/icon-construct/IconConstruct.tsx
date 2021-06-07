import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";

export const IconConstruct: React.FC<any> = ({ children, title, onClick }) => {
  return (
    <Tooltip title={title}>
      <IconButton onClick={onClick}>{children}</IconButton>
    </Tooltip>
  );
};
