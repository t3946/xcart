import React from "react";
import { IconButton, Tooltip } from "@material-ui/core";
import StarIcon from "@material-ui/icons/Star";

export const StarIc = () => {
  return (
    <Tooltip title="Delete">
      <IconButton>
        <StarIcon />
      </IconButton>
    </Tooltip>
  );
};
