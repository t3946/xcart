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

  document.body.style.overflowY = isVisible ? "hidden" : "";

  return (
    <div>
      <TransitionFade show={isVisible}>
        <div className={"shadow-panel"} onClick={clickHandler} />
      </TransitionFade>
    </div>
  );
};

export default ShadowPanel;
