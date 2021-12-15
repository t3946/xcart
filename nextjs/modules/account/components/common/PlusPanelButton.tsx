import React from "react";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import Plus from "@modules/icon/components/account/plus/Plus";
import classnames from "classnames";
import Styles from "@modules/account/components/common/PlusPanelButton.module.scss";

interface IProps {
  onClick?: any;
  text?: string;
  classes?: {
    container?: any;
    icon?: any;
    text?: any;
  };
}

const PlusPanelButton: React.FC<IProps> = function (props: IProps) {
  const { onClick, text } = props;
  const classes = {
    icon: props.classes?.icon,
    text: [props.classes?.text],
  };

  return (
    <RectangularButton
      onClick={onClick}
      classNames={{ container: props.classes?.container }}
      body={
        <div className={Styles.container}>
          <Plus className={classes.icon} />
          <div className={classnames(classes.text)}>{text}</div>
        </div>
      }
    />
  );
};

export default PlusPanelButton;
