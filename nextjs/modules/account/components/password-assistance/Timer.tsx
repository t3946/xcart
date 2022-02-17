import React from "react";
import cn from "classnames";

interface IProps {
  timeLeftS: number;
  setTimeLeftS: (timeLeftS: number) => void;
  action: () => void;
  className: any;
  isActive: boolean;
}

const Timer: React.FC<any> = function (props: IProps) {
  const { timeLeftS, setTimeLeftS, className, action, isActive } = props;

  React.useEffect(() => {
    let interval: any = null;

    if (isActive) {
      interval = setInterval(() => {
        //update timer in parent component
        setTimeLeftS(timeLeftS - 1);
      }, 1000);
    } else if (!isActive && timeLeftS !== 0) {
      clearInterval(interval);
    }

    //destroy timer with component
    return () => clearInterval(interval);
  }, [isActive, timeLeftS]);

  function formatTime(timeS: number) {
    const minutes = Math.floor(timeS / 60);
    const seconds = timeS % 60;

    return `${minutes}:${seconds >= 10 ? seconds : "0" + seconds}`;
  }

  const classes = {
    link: [
      "common-link",
      {
        "common-link__disabled": timeLeftS > 0,
      },
    ],
    timer: [
      "resend-otp-left-time ms-1",
      {
        "d-none": timeLeftS <= 0,
      },
    ],
  };

  function actionHandler() {
    if (timeLeftS > 0) {
      return;
    }

    action();
  }

  return (
    <div className={cn(className)}>
      <span className={cn(classes.link)} onClick={actionHandler}>
        Send SMS
      </span>

      <span className={cn(classes.timer)}>{formatTime(timeLeftS)}</span>
    </div>
  );
};

export default Timer;
