import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import ForwardIcon from "@material-ui/icons/Forward";

export const ForwardIc = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <ForwardIcon />
      </IconButton>
    </Tooltip>
  );
};
