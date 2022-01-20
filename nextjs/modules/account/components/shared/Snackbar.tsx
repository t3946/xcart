import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useDispatch } from "react-redux";
import { hideSnackbar } from "@redux/actions/account-actions/SnackbarActions";
import AlertCheck from "@modules/icon/components/account/check/AlertCheck";
import AlertExclamationTriangle from "@modules/icon/components/account/exclamation-triangle/AlertExclamationTriangle";
import cn from "classnames";
import { Alert as BAlert } from "react-bootstrap";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import SnackbarMobile from "@modules/account/components/shared/SnackbarMobile";

import Styles from "@modules/account/components/shared/Snackbar.module.scss";

export enum VariantsEnum {
  success = "success",
  warning = "warning",
  error = "error",
}

export function alertIconTemplate(variant: VariantsEnum): React.ReactElement {
  const classes = { icon: ["alert_icon", "alert-icon", Styles.alertIcon] };
  switch (variant) {
    case VariantsEnum.success:
      return (
        <AlertCheck className={cn([classes.icon, "alert-icon__success"])} />
      );
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

const Snackbar: React.FC = () => {
  const [displaying, setDisplaing] = React.useState<boolean>(false);
  const dispatch = useDispatch();
  const snackbar = useSelectorAccount((e) => e.snackbar);
  const timoutRef = React.useRef<NodeJS.Timeout>();
  const alertClass = `account-alert__${snackbar.alert?.variant}`;
  const breakpoint = useBreakpoint();
  const [isBrowser, setIsBrowser] = React.useState(false);

  React.useEffect(() => {
    setIsBrowser(true);
    return timoutRef.current && clearTimeout(timoutRef.current);
  }, []);

  const clearSnackbar = () => {
    if (timoutRef.current) {
      clearTimeout(timoutRef.current);
      timoutRef.current = undefined;
      setDisplaing(false);
    }
  };

  React.useEffect(() => {
    clearSnackbar();
    if (snackbar.alert) {
      setDisplaing(true);
      timoutRef.current = setTimeout(() => {
        dispatch(hideSnackbar());
      }, snackbar.alert.duration);
    }
  }, [snackbar]);

  const classes = {
    alert: [
      "d-flex justify-content-center account-alert",
      alertClass,
      Styles.snack,
      {
        "p-0": !displaying,
        [Styles.snack_open]: displaying,
      },
    ],
  };

  function contentTemplate() {
    switch (snackbar.alert?.variant) {
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
            {alertIconTemplate(snackbar.alert?.variant)}
            {snackbar.alert?.message}
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
              {alertIconTemplate(snackbar.alert?.variant)}
              There was a problem
            </p>
            <p className="alert-content m-0">
              {{ __html: snackbar.alert?.message }}
            </p>
          </>
        );
    }
  }

  if (isBrowser) {
    return breakpoint({
      xs: () => {
        return <SnackbarMobile />;
      },
      sm: undefined,
      md: () => (
        <BAlert variant={snackbar.alert?.variant} className={cn(classes.alert)}>
          {contentTemplate()}
        </BAlert>
      ),
      lg: undefined,
      xl: undefined,
      xxl: undefined,
    });
  }

  return (
    <BAlert variant={snackbar.alert?.variant} className={cn(classes.alert)}>
      {contentTemplate()}
    </BAlert>
  );
};

export default Snackbar;
