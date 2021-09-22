import React from "react";
import { useDispatch, useSelector } from "react-redux";
import HideAllMenu from "@client/jsx/modules/account/utils/hide-all-menu";
import TransitionFade from "@client/modules/account/components/shared/TransitionFade";

const ShadowPanel = (): any => {
  const dispatch = useDispatch();
  const isVisible = useSelector(
    (e: Record<any, any>) => e.shadowPanel.isVisible
  );

  function clickHandler() {
    HideAllMenu(dispatch);
  }

  const topHeader = document.getElementById("top-header");

  if (isVisible) {
    document.body.style.overflowY = "hidden";
    topHeader && topHeader.classList.add("header__shadow-panel-visible");
  } else {
    document.body.style.overflowY = "";
    topHeader && topHeader.classList.remove("header__shadow-panel-visible");
  }

  const transitionFadeStyles = {
    left: 0,
    top: 0,
    width: "100%",
    height: "100%",
    zIndex: 2,
  };

  return (
    <TransitionFade show={isVisible} styles={transitionFadeStyles}>
      <div className={"shadow-panel"} onClick={clickHandler} />
    </TransitionFade>
  );
};

export default ShadowPanel;
