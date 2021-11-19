import React from "react";
import { Transition } from "react-transition-group";
import classnames from "classnames";

interface IProps {
  show: boolean;
  duration?: number;
  children: React.ReactElement;
  containerClasses?: string;
}

const TransitionSlide: React.FC<IProps> = function (
  props: IProps
) {
  const { show, containerClasses } = props;
  let { duration } = props;

  if (duration === undefined) {
    duration = 300;
  }

  const alertContainer = React.useRef<HTMLDivElement>();
  const [height, setHeight] = React.useState(null);
  const defaultStyle = {
    transition: `all ${duration}ms ease-out`,
    height: 0,
    opacity: 0,
    overflow: "hidden",
  };

  const transitionStyles = {
    entering: { height, opacity: 1 },
    entered: { height, opacity: 1 },
    exiting: { height: 0, opacity: 0 },
    exited: { height: 0, opacity: 0 },
  };

  React.useEffect(function () {
    if (height !== alertContainer.current.offsetHeight) {
      setHeight(alertContainer.current.offsetHeight);
    }
  });

  return (
    <Transition in={show} timeout={duration}>
      {(state) => (
        <div
          style={{
            ...defaultStyle,
            ...transitionStyles[state],
          }}
        >
          <div ref={alertContainer} className={classnames(containerClasses)}>
            {props.children}
          </div>
        </div>
      )}
    </Transition>
  );
};

export default TransitionSlide;
