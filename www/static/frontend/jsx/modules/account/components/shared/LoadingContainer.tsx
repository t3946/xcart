import React from "react";
import classnames from "classnames";

export const LoadingContainer = ({
  children,
  loading,
  classContainer = undefined,
}) => {
  return (
    <div className={classnames(classContainer, "item-container-loading")}>
      {children}
      <div className={`item-loading ${loading && "item-loading-is_loading"}`} />
    </div>
  );
};
