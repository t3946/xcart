import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import PrintIcon from "@material-ui/icons/Print";

export const PrintIc: React.FC = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <PrintIcon />
      </IconButton>
    </Tooltip>
  );
};
