import * as React from "react";
import Styles from "@client/jsx/modules/ui/SliderSwitchButton.module.scss";
import cn from "classnames";

const SliderSwitchButton: React.FC = function () {
  const [checked, setChecked] = React.useState(false);

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
    <div
      className={Styles.border}
      onClick={() => {
        setChecked(!checked);
      }}
    >
      <div className={Styles.label}>
        <input type="checkbox" />

        <b className={cn(classes.caption, classes.captionEnabled)}>yes</b>

        <span className={cn(classes.ball)} />

        <b className={cn(classes.caption, classes.captionDisabled)}>no</b>

        <div className={cn(classes.background)} />
      </div>
    </div>
  );
};

export default SliderSwitchButton;
