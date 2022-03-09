import React from "react";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { useDispatch, useSelector } from "react-redux";
import TimesIcon from "@modules/icon/components/account/ModalTimes";
import StoreInterface from "@modules/account/ts/types/store.type";
import classnames from "classnames";
import Styles from "@modules/account/components/shared/AlertMobile.module.scss";
import {
  alertIconTemplate,
  VariantsEnum,
} from "@modules/account/components/shared/Alert";

const AlertMobile: React.FC = function () {
  const dispatch = useDispatch();
  const mobileAlert = useSelector((e: StoreInterface) => e.mobileAlert);
  const { isVisible } = mobileAlert;
  const initialAlertValue = {
    variant: VariantsEnum.success,
    message: "",
  };
  const alert = mobileAlert.alert || initialAlertValue;

  function closeAlert() {
    HideAllMenu(dispatch);
  }

  const classes = {
    container: [
      isVisible ? "d-flex" : "d-none",
      "mobile-alert account-inner-page_mobile-alert w-100 pos-relative",
      Styles.mobileAlert_decision,
    ],
  };

  function contentTemplate() {
    switch (alert.variant) {
      case VariantsEnum.success:
        return (
          <>
            <p
              className={
                "mb-10 position-relative mobile-alert-title d-flex align-items-end"
              }
            >
              {alertIconTemplate(alert.variant)}
              Success
            </p>

            <p className={"m-0"}>{alert.message}</p>
          </>
        );

      case VariantsEnum.warning:
        return (
          <>
            <p className={"mb-10 position-relative mobile-alert-title"}>
              {alertIconTemplate(alert.variant)}
              Warning
            </p>

            <p className={"m-0"}>{alert.message}</p>
          </>
        );

      case VariantsEnum.error:
        return (
          <>
            <p
              className={
                "mb-10 position-relative mobile-alert-title mobile-alert-title__error"
              }
            >
              {alertIconTemplate(alert.variant)}
              There was a problem
            </p>

            <p className={"m-0"}>{alert.message}</p>
          </>
        );

      case VariantsEnum.decisionWarning:
      case VariantsEnum.decisionSuccess:
        return (
          <>
            <p
              className={classnames([
                "m-0",
                "text-center",
                Styles.mobileAlertContent,
              ])}
            >
              {alertIconTemplate(alert.variant)} {alert.message}
            </p>
          </>
        );
    }
  }

  return (
    <div className={classnames(classes.container)}>
      <span
        className={classnames([
          "mobile-alert_close-button",
          "mobile-alert-close",
          "shrink-by-active",
          Styles.mobileAlertClose,
        ])}
        onClick={closeAlert}
      >
        <TimesIcon />
      </span>
      {contentTemplate()}
    </div>
  );
};

export default AlertMobile;
