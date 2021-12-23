import React, { Fragment } from "react";
import { Skeleton } from "@mui/material";

export const LoadCartItems: React.FC = () => {
  return (
    <Fragment>
      {Array(20)
        .fill(1)
        .map(() => {
          return (
            <Skeleton
              sx={{ my: 1 }}
              width="100%"
              height={48}
              variant={"rectangular"}
            />
          );
        })}
    </Fragment>
  );
};
