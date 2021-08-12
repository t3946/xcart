import React from "react";
import { useDispatch, useSelector } from "react-redux";
import classnames from "classnames";
import { hideShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";

const ShadowPanel = (): any => {
  const dispatch = useDispatch();
  const shadowPanel = useSelector((e: Record<any, any>) => e.shadowPanel);
  const shadowPanelClasses = [
    "shadow-panel",
    shadowPanel.isVisible ? "d-block" : "d-none",
  ];

  return (
    <div
      style={{ zIndex: shadowPanel.zIndex }}
      className={classnames(shadowPanelClasses)}
      onClick={() => dispatch(hideShadowPanelAction())}
    />
  );
};

export default ShadowPanel;
