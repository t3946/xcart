import React from "react";
import { useDispatch, useSelector } from "react-redux";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import Styles from "@modules/account/components/shared/ShadowPanel.module.scss";

const ShadowPanel = (): any => {
  const dispatch = useDispatch();
  const isVisible = useSelector(
    (e: Record<any, any>) => e.shadowPanel.isVisible
  );

  function clickHandler() {
    HideAllMenu(dispatch);
  }

  const transitionFadeStyles = {
    position: "absolute",
    left: 0,
    top: 0,
    width: "100%",
    height: "100%",
    zIndex: 2,
  };

  return (
    <TransitionFade show={isVisible} styles={transitionFadeStyles}>
      <div className={Styles.shadowPanel} onClick={clickHandler} />
    </TransitionFade>
  );
};

export default ShadowPanel;
