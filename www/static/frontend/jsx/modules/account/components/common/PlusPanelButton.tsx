import React from "react";
import Plus from "@client/jsx/modules/icon/components/account/plus/Plus";
import classnames from "classnames";

interface PropsInterface {
  onClick?: any;
  text?: string;
  classes?: {
    icon?: any;
    container?: any;
    text?: any;
  };
}

const PlusPanelButton: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  const { onClick, text } = props;
  const classes = {
    icon: props.classes?.icon,
    container: [
      "plus-panel-button-container",
      "align-items-center",
      "d-flex",
      "justify-content-center",
      props.classes?.container,
    ],
    text: ["plus-panel-button-text", props.classes?.text],
  };

  return (
    <div onClick={onClick} className={classnames(classes.container)}>
      <Plus className={classes.icon} />

      <div className={classnames(classes.text)}>{text}</div>
    </div>
  );
};

export default PlusPanelButton;
