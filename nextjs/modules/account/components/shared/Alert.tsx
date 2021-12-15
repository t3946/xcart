import TransitionSlide from "@modules/account/components/shared/TransitionSlide";
import cn from "classnames";
import { Alert as BAlert } from "react-bootstrap";
import React from "react";
import AlertCheck from "@modules/icon/components/account/check/AlertCheck";
import AlertExclamationTriangle from "@modules/icon/components/account/exclamation-triangle/AlertExclamationTriangle";
import Styles from "@modules/account/components/shared/Alert.module.scss";

export enum VariantsEnum {
  success = "success",
  warning = "warning",
  error = "error",
  decisionSuccess = "decisionSuccess",
  decisionWarning = "decisionWarning",
}

export function alertIconTemplate(variant: VariantsEnum): React.ReactElement {
  const classes = { icon: ["alert_icon", "alert-icon", Styles.alertIcon] };
  switch (variant) {
    case VariantsEnum.decisionSuccess:
    case VariantsEnum.success:
      return (
        <AlertCheck className={cn([classes.icon, "alert-icon__success"])} />
      );

    case VariantsEnum.decisionWarning:
    case VariantsEnum.warning:
      return (
        <AlertExclamationTriangle
          className={cn([classes.icon, "alert-icon__warning"])}
        />
      );
    case VariantsEnum.error:
      return (
        <AlertExclamationTriangle
          className={cn([classes.icon, "alert-icon__error"])}
        />
      );
  }
}

interface IProps {
  show: boolean;
  variant: VariantsEnum;
  message: string;
  classes?: {
    alert?: any;
    container?: any;
  };
}

const Alert: React.FC<IProps> = function (props: IProps) {
  const { show, variant, message } = props;

  let alertClass: string;

  switch (variant) {
    case VariantsEnum.decisionSuccess:
      alertClass = Styles.accountAlert_decisionSuccess;
      break;
    case VariantsEnum.decisionWarning:
      alertClass = Styles.accountAlert_decisionWarning;
      break;
    default:
      alertClass = `account-alert__${variant}`;
  }

  const classes = {
    container: props.classes?.container,
    alert: [
      "d-flex justify-content-center account-alert",
      alertClass,
      props.classes?.alert,
    ],
  };

  function contentTemplate() {
    switch (variant) {
      case VariantsEnum.decisionSuccess:
      case VariantsEnum.decisionWarning:
      case VariantsEnum.success:
      case VariantsEnum.warning:
        return (
          <p
            className={cn([
              Styles.alertContent,
              "position-relative",
              "m-0",
              "alert-content",
            ])}
          >
            {alertIconTemplate(variant)}
            {message}
          </p>
        );
      case VariantsEnum.error:
        return (
          <>
            <p
              className={cn([
                Styles.alertContent,
                "position-relative",
                "m-0",
                "alert-header",
                "alert-header__error",
              ])}
            >
              {alertIconTemplate(variant)}
              There was a problem
            </p>
            <p className="alert-content m-0">{{ __html: message }}</p>
          </>
        );
    }
  }

  return (
    <TransitionSlide show={show} containerClasses={classes.container}>
      <BAlert variant={variant} className={cn(classes.alert)}>
        {contentTemplate()}
      </BAlert>
    </TransitionSlide>
  );
};

export default Alert;
