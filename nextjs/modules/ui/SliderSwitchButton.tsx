import * as React from "react";
import Styles from "@modules/ui/SliderSwitchButton.module.scss";
import cn from "classnames";

interface IProps {
  checked: boolean;
  name: string;
  onChange: (e: React.ChangeEvent) => void;
  disabled?: boolean;
}

const SliderSwitchButton: React.FC<IProps> = function (props: IProps) {
  const { checked, name, onChange, disabled } = props;

  const classes = {
    caption: ["d-flex", "align-items-center", "h-100", "top-0", Styles.caption],
    captionEnabled: [
      {
        [Styles.caption_enabledActive]: checked === true,
        [Styles.caption_enabledInactive]: checked === false,
      },
    ],
    captionDisabled: [
      {
        [Styles.caption_disabledActive]: checked === false,
        [Styles.caption_disabledInactive]: checked === true,
      },
    ],
    ball: [
      Styles.ball,
      {
        [Styles.ball_enabled]: checked === true,
        [Styles.ball_disabled]: checked === false,
      },
    ],
    background: [
      Styles.background,
      "top-0",
      {
        [Styles.background_enabled]: checked === true,
        [Styles.background_disabled]: checked === false,
      },
    ],
  };

  return (
    <label className={Styles.border}>
      <div className={Styles.label}>
        <input
          name={name}
          type="checkbox"
          disabled={disabled}
          checked={checked}
          onChange={onChange}
        />

        <b className={cn(classes.caption, classes.captionEnabled)}>yes</b>

        <span className={cn(classes.ball)} />

        <b className={cn(classes.caption, classes.captionDisabled)}>no</b>

        <div className={cn(classes.background)} />
      </div>
    </label>
  );
};

export default SliderSwitchButton;
