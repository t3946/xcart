import React from "react";

export const NoItemsBlock = () => {
  return (
    <div className="no-items-block-container">
      <img
        className="no-items-block-img"
        src="/static/frontend/images/icons/account/no-items.svg"
      />
      <div>
        There are no items in this List. Add items you want to shop for.
      </div>
    </div>
  );
};
