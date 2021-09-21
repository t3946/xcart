import React from "react";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { useDispatch, useSelector } from "react-redux";
import TimesIcon from "@client/modules/icon/components/account/ModalTimes";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import classnames from "classnames";
import {
  alertIconTemplate,
  VariantsEnum,
} from "@client/modules/account/utils/alert";

const AlertMobile: React.FC = function () {
  const dispatch = useDispatch();
  const mobileAlert = useSelector((e: AccountStore) => e.mobileAlert);
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
    }
  }

  return (
    <div className={classnames(classes.container)}>
      <span
        className="mobile-alert_close-button mobile-alert-close shrink-by-active"
        onClick={closeAlert}
      >
        <TimesIcon />
      </span>
      {contentTemplate()}
    </div>
  );
};

export default AlertMobile;
