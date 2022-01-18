import React from "react";
import {
  alertIconTemplate,
  VariantsEnum,
} from "@modules/account/components/shared/Snackbar";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useDispatch } from "react-redux";
import { hideSnackbar } from "@redux/actions/account-actions/SnackbarActions";
import TimesIcon from "@modules/icon/components/account/ModalTimes";
import cn from "classnames";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import Styles from "@modules/account/components/shared/SnackbarMobile.module.scss";

const SnackbarMobile: React.FC = () => {
  const snackbar = useSelectorAccount((e) => e.snackbar);
  const [displaying, setDisplaying] = React.useState<boolean>(false);
  const dispatch = useDispatch();
  React.useEffect(() => {
    if (snackbar.alert) {
      dispatch(setVisibleShadowPanelAction(true));
      setDisplaying(true);
    } else {
      dispatch(setVisibleShadowPanelAction(false));
      setDisplaying(false);
    }
  }, [snackbar]);

  function contentTemplate() {
    switch (snackbar.alert?.variant) {
      case VariantsEnum.success:
      case VariantsEnum.warning:
        return (
          <>
            <p
              className={cn(["m-0", "text-center", Styles.mobileAlertContent])}
            >
              {alertIconTemplate(snackbar.alert?.variant)}{" "}
              {snackbar.alert?.message}
            </p>
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
              {alertIconTemplate(snackbar.alert?.variant)}
              There was a problem
            </p>

            <p className={"m-0"}>{snackbar.alert?.message}</p>
          </>
        );
      default:
        return (
          <>
            <p
              className={cn(["m-0", "text-center", Styles.mobileAlertContent])}
            >
              {snackbar.alert?.message}
            </p>
          </>
        );
    }
  }
  return (
    <div
      className={cn(
        displaying ? "d-flex" : "d-none",
        "mobile-alert account-inner-page_mobile-alert w-100 pos-relative",
        Styles.mobileAlert_decision
      )}
    >
      <span
        className={cn([
          "mobile-alert_close-button",
          "mobile-alert-close",
          "shrink-by-active",
          Styles.mobileAlertClose,
        ])}
        onClick={() => dispatch(hideSnackbar())}
      >
        <TimesIcon />
      </span>
      {contentTemplate()}
    </div>
  );
};

export default SnackbarMobile;
