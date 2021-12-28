import React from "react";
import classnames from "classnames";

interface IProps {
  leftResendTime: number;
  decLeftResendTime: () => void;
  sendOneTimePassword: (arg0: any) => void;
}

const ResendOtpButton: React.FC<any> = function (props: IProps) {
  const { leftResendTime, decLeftResendTime, sendOneTimePassword } = props;
  const [disabled, setDisabled] = React.useState(false);

  function updateLeftNewSendTime() {
    if (leftResendTime <= 0) {
      clearInterval(leftResendTimeInterval);
      return;
    }

    decLeftResendTime();
  }

  const leftResendTimeInterval = setInterval(updateLeftNewSendTime, 1000);

  React.useEffect(function () {
    return () => {
      clearInterval(leftResendTimeInterval);
    };
  });

  function formatTime(time: number) {
    const minutes = Math.floor(time / 60);
    const seconds = time % 60;

    return `${minutes}:${seconds >= 10 ? seconds : "0" + seconds}`;
  }

  const classes = {
    link: [
      "common-link",
      {
        "common-link__disabled": leftResendTime > 0,
      },
    ],
    timer: [
      "resend-otp-left-time ms-1",
      {
        "d-none": leftResendTime <= 0,
      },
    ],
  };

  return (
    <>
      <span
        className={classnames(classes.link)}
        onClick={() => {
          if (disabled) {
            return;
          }

          if (leftResendTime === 0) {
            sendOneTimePassword(function () {
              setDisabled(false);
            });
            setDisabled(true);
          }
        }}
      >
        Resend OTP
      </span>

      <span className={classnames(classes.timer)}>
        {formatTime(leftResendTime)}
      </span>
    </>
  );
};

export default ResendOtpButton;
