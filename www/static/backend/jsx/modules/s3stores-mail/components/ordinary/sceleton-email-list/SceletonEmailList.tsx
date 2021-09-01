import React from "react";
import { SceletonEmailListItem } from "@s3stores-mail/components/simple";

export const SceletonEmailList: React.FC<any> = ({ itemsCount }) => {
  return (
    <div>
      {Array(itemsCount)
        .fill(1)
        .map(() => {
          return <SceletonEmailListItem />;
        })}
    </div>
  );
};
