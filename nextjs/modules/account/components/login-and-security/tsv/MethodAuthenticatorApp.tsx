import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/login-and-security/TSVSettings.module.scss";
import Link from "next/link";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { setPreferredMethodAction } from "@redux/actions/account-actions/TSVActions";
import { useDispatch } from "react-redux";
import { AxiosResponse } from "axios";
import { userSetAction } from "@redux/actions/account-actions/UserActions";

const MethodPhoneNumberAppend: React.FC<any> = function () {
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();

  function setPreferredMethod() {
    dispatch(
      setPreferredMethodAction({
        data: {
          method: "authenticator_app",
        },
        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
        },
      })
    );
  }

  function actionsTemplate() {
    const actions = [];

    if (!user.phone) {
      actions.push(
        <Link
          href={
            "/login-and-security/two-step-verification-settings-preferred-method"
          }
        >
          <a className={Styles.commonLink}>Change</a>
        </Link>
      );
    } else {
      actions.push(
        <span className={Styles.commonLink} onClick={setPreferredMethod}>
          Change
        </span>
      );
    }
    return (
      <div className="col-6 col-md-2 col-lg-2 d-flex justify-content-end justify-content-lg-start">
        {actions}
      </div>
    );
  }

  return (
    <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
      <div
        className={cn(
          Styles.text,
          "col-12",
          "col-md-3",
          "ps-lg-0",
          "mb-3",
          "mb-lg-0"
        )}
      >
        Authenticator App
        {user.tsv_count ? <div>{user.tsv_count} app(s) enrolled</div> : null}
      </div>

      <div className="col-6 col-md-4 col-lg-5 d-flex justify-content-md-center">
        <Link href={"/login-and-security/two-step-verification-add-new"}>
          <a className={Styles.commonLink}>Add new app</a>
        </Link>
      </div>

      {actionsTemplate()}

      <div className="d-none d-lg-block col-lg-2 pe-0" />
    </div>
  );
};

export default MethodPhoneNumberAppend;
