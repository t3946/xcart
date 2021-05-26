import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";

export const IconConstruct: React.FC<any> = ({ children, title }) => {
  return (
    <Tooltip title={title}>
      <IconButton>{children}</IconButton>
    </Tooltip>
  );
};
