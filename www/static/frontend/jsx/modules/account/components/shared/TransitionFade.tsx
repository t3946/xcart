import React from "react";
import { Transition } from "react-transition-group";

interface IProps {
  show: boolean;
  durationMs?: number;
  children: React.ReactElement;
  styles?: Record<any, any>;
}

const TransitionFade: React.FC<IProps> = function (
  props: IProps
) {
  const { show, styles } = props;
  const defaultDurationMs = 150;
  const durationMs = props.durationMs || defaultDurationMs;
  const defaultStyle = {
    transition: `all ${durationMs}ms ease-out`,
    opacity: 0,
    display: "none",
    ...styles,
  };

  const transitionStyles = {
    entering: { opacity: 0, display: "block" },
    entered: { opacity: 1, display: "block" },
    exiting: { opacity: 0, display: "block" },
    exited: { opacity: 0, display: "none" },
  };

  return (
    <Transition in={show} timeout={durationMs}>
      {(state) => {
        return (
          <div
            style={{
              ...defaultStyle,
              ...transitionStyles[state],
            }}
            className={"bd-filter"}
          >
            {props.children}
          </div>
        );
      }}
    </Transition>
  );
};

export default TransitionFade;
