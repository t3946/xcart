import React from "react";
import { useDispatch, useSelector } from "react-redux";
import classnames from "classnames";
import HideAllMenu from "@client/jsx/modules/account/utils/hide-all-menu";

const ShadowPanel = (): any => {
  const dispatch = useDispatch();
  const shadowPanel = useSelector((e: Record<any, any>) => e.shadowPanel);
  const shadowPanelClasses = [
    "shadow-panel",
    shadowPanel.isVisible ? "d-block" : "d-none",
  ];

  function clickHandler() {
    HideAllMenu(dispatch);
  }

  return (
    <div
      style={{ zIndex: shadowPanel.zIndex }}
      className={classnames(shadowPanelClasses)}
      onClick={clickHandler}
    />
  );
};

export default ShadowPanel;
