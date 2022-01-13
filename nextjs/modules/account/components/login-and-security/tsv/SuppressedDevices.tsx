import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Button, { EType } from "@modules/ui/forms/Button";
import { requireForAllAction } from "@redux/actions/account-actions/TSVActions";
import { useDispatch } from "react-redux";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { AxiosResponse } from "axios";
import cn from "classnames";

import StylesTSVSettings from "@modules/account/components/login-and-security/TSVSettings.module.scss";

const SuppressedDevices: React.FC<any> = function () {
  const user = useSelectorAccount((e) => e.user);
  const [disabled, setDisabled] = React.useState(user.tsv_suppressed === 0);
  const dispatch = useDispatch();

  function requireForAllSubmit() {
    setDisabled(true);

    dispatch(
      requireForAllAction({
        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
        },
      })
    );
  }

  return (
    <div className="row mx-0 mb-4">
      <div
        className={cn(
          StylesTSVSettings.text,
          "col-12",
          "col-lg-6",
          "d-lg-flex",
          "align-items-center",
          "ps-lg-0",
          "mb-14",
          "mb-md-20",
          "mb-lg-0"
        )}
      >
        <b>You have {user.tsv_suppressed} devices where OTP is suppressed</b>
      </div>

      <div className="col-12 col-lg-6 d-md-flex justify-content-lg-end px-1 px-md-3 pe-lg-0">
        <Button
          className={cn(
            StylesTSVSettings.button_requireOtp,
            "form-button",
            "form-button__theme-dark-grey",
            "w-100",
            "w-md-auto",
            "px-0 px-md-3"
          )}
          onClick={requireForAllSubmit}
          disabled={disabled}
          type={EType.themeDarkGrey}
        >
          Require OTP on all devices
        </Button>
      </div>
    </div>
  );
};

export default SuppressedDevices;
