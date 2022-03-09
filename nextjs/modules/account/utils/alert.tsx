import AlertCheck from "@modules/icon/components/account/check/AlertCheck";
import AlertExclamationTriangle from "@modules/icon/components/account/exclamation-triangle/AlertExclamationTriangle";
import React from "react";

export enum VariantsEnum {
  success = "success",
  warning = "warning",
  error = "error",
}

export function alertIconTemplate(variant: VariantsEnum): React.ReactElement {
  switch (variant) {
    case VariantsEnum.success:
      return (
        <AlertCheck className={"alert_icon alert-icon alert-icon__success"} />
      );
    case VariantsEnum.warning:
      return (
        <AlertExclamationTriangle
          className={"alert_icon alert-icon alert-icon__warning"}
        />
      );
    case VariantsEnum.error:
      return (
        <AlertExclamationTriangle
          className={"alert_icon alert-icon alert-icon__error"}
        />
      );
  }
}
