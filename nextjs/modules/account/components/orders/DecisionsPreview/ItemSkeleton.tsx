import React from "react";

const SkeletonItem: React.FC<any> = React.forwardRef(function () {
  return <div className={"skeleton-box"} />;
});

export default SkeletonItem;
