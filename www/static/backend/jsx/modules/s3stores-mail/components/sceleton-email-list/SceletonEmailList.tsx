import React from "react";
import { SceletonItem } from "@s3stores-mail/components/sceleton-item/SceletonItem";

export const SceletonEmailList = ({ itemsCount }) => {
  return (
    <div>
      {Array(itemsCount)
        .fill(1)
        .map(() => {
          return <SceletonItem />;
        })}
    </div>
  );
};
