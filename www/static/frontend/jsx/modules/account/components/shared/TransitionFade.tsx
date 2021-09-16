import React from "react";
import { Transition } from "react-transition-group";

interface PropsInterface {
  show: boolean;
  durationMs?: number;
  children: React.ReactElement;
}

const TransitionFade: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  const { show } = props;
  const defaultDurationMs = 300;
  const durationMs = props.durationMs || defaultDurationMs;
  const defaultStyle = {
    transition: `all ${durationMs}ms ease-out`,
    opacity: 0,
    display: "none",
  };

  const transitionStyles = {
    entering: { opacity: 0, display: "block" },
    entered: { opacity: 1, display: "block" },
    exiting: { opacity: 0, display: "block" },
    exited: { opacity: 0, display: "none" },
  };

  return (
    <Transition in={show} timeout={durationMs}>
      {(state) => (
        <div
          style={{
            ...defaultStyle,
            ...transitionStyles[state],
          }}
        >
          {props.children}
        </div>
      )}
    </Transition>
  );
};

export default TransitionFade;
