import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";

export const IconConstruct: React.FC<any> = ({ children, title, onClick }) => {
  return (
    <Tooltip title={title}>
      <IconButton className="icon-button" onClick={onClick}>
        {children}
      </IconButton>
    </Tooltip>
  );
};
