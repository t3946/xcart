import TransitionSlide from "@client/modules/account/components/shared/TransitionSlide";
import classnames from "classnames";
import { Alert as BAlert } from "react-bootstrap";
import React from "react";
import {
  alertIconTemplate,
  VariantsEnum,
} from "@client/modules/account/utils/alert";

interface PropsInterface {
  show: boolean;
  variant: VariantsEnum;
  message: string;
  classes?: {
    alert?: any;
    container?: any;
  };
}

const Alert: React.FC<PropsInterface> = function (props: PropsInterface) {
  const { show, variant, message } = props;
  const classes = {
    container: props.classes?.container,
    alert: [
      "d-flex justify-content-center account-alert",
      `account-alert__${variant}`,
      props.classes?.alert,
    ],
  };

  function contentTemplate() {
    switch (variant) {
      case VariantsEnum.success:
      case VariantsEnum.warning:
        return (
          <p className={"position-relative m-0 alert-content"}>
            {alertIconTemplate(variant)}
            {message}
          </p>
        );
      case VariantsEnum.error:
        return (
          <>
            <p
              className={
                "position-relative m-0 alert-header alert-header__error"
              }
            >
              {alertIconTemplate(variant)}
              There was a problem
            </p>
            <p className="alert-content m-0">{message}</p>
          </>
        );
    }
  }

  return (
    <TransitionSlide show={show} containerClasses={classes.container}>
      <BAlert variant={variant} className={classnames(classes.alert)}>
        {contentTemplate()}
      </BAlert>
    </TransitionSlide>
  );
};

export default Alert;
