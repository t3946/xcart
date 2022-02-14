import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import cn from "classnames";
import Styles from "@modules/account/components/login-and-security/TSVSettings.module.scss";
import MethodPhoneNumberDefault from "@modules/account/components/login-and-security/tsv/MethodPhoneNumberDefault";
import MethodPhoneNumberAppend from "@modules/account/components/login-and-security/tsv/MethodPhoneNumberAppend";
import MethodAuthenticatorApp from "@modules/account/components/login-and-security/tsv/MethodAuthenticatorApp";

enum EMethodType {
  APP = "authenticator_app",
  PHONE = "phone_number",
}

export enum EListType {
  PREFERRED,
  BACKUP,
}

interface IProps {
  type: EListType;
}

const Methods: React.FC<IProps> = function (props) {
  const user = useSelectorAccount((e) => e.user);
  const { type } = props;
  const headers = {
    [EListType.PREFERRED]: "Preferred method",
    [EListType.BACKUP]: "Backup method",
  };
  const appMethods = [<MethodAuthenticatorApp key={"method-auth-app"} />];
  const phoneMethods = [];

  if (user.phone) {
    phoneMethods.push(
      <MethodPhoneNumberDefault key={"method-phone-default"} />
    );
  }

  if (user.appendPhone) {
    phoneMethods.push(<MethodPhoneNumberAppend key={"method-phone-append"} />);
  }

  let methods: any = [];

  switch (user.tsv_preferred_method) {
    case EMethodType.APP:
      methods = type === EListType.PREFERRED ? appMethods : phoneMethods;
      break;
    case EMethodType.PHONE:
      methods = type === EListType.PREFERRED ? phoneMethods : appMethods;
      break;
  }

  if (methods.length === 0) {
    return null;
  }

  return (
    <>
      <div className="row two-step-row__bordered two-step-row__header mx-0 pb-lg-2 lg-2">
        <div className="col-12 px-lg-0">
          <h3
            className={cn(
              Styles.caption,
              "mb-0",
              "content-h3",
              "tsv-settings-box-header"
            )}
          >
            {headers[type]}
          </h3>
        </div>
      </div>

      {methods}
    </>
  );
};

export default Methods;
