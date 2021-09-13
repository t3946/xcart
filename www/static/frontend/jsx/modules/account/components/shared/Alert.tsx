import SlideTransition from "@client/modules/account/components/shared/SlideTransition";
import AlertCheck from "@client/modules/icon/components/account/check/AlertCheck";
import classnames from "classnames";
import { Alert as BAlert } from "react-bootstrap";
import React from "react";

interface PropsInterface {
  show: boolean;
  variant: string;
  message: string;
  classes?: {
    alert?: any;
    container?: any;
  };
}

const Alert: React.FC<PropsInterface> = function (props: PropsInterface) {
  const { show, variant, message, classes } = props;

  return (
    <SlideTransition show={show} containerClasses={classes?.container}>
      <BAlert
        variant={variant}
        className={classnames(
          "alert-success__account d-flex justify-content-center",
          classes?.alert
        )}
      >
        <p className={"position-relative m-0 alert-content"}>
          <AlertCheck className={"alert-success_icon alert-success-icon"} />
          {message}
        </p>
      </BAlert>
    </SlideTransition>
  );
};

export default Alert;
