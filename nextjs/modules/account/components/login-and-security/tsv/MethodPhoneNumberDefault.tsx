import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/login-and-security/TSVSettings.module.scss";
import { formatPhone } from "@utils/phoneNumber";
import Tooltip from "@components/common/tooltip/Tooltip";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const PhoneNumberAppend: React.FC<any> = function () {
  const user = useSelectorAccount((e) => e.user);

  if (!user.phone) {
    return null;
  }

  return (
    <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
      <div
        className={cn(
          Styles.text,
          "col-12 col-md-3 pe-md-0 ps-lg-0 mb-12 mb-lg-0"
        )}
      >
        {formatPhone(user.phone, true)}
        <br />
        Sent by text message
      </div>

      <div className="col-6 col-md-4 col-lg-5 text-md-center">
        <span className="d-block d-md-inline-block">Phone number</span>

        <Tooltip
          overlay={
            <div>
              <h2 className={"common-tooltip-header"}>
                <b>Your phone number</b>
              </h2>

              <p className={"text-align--left auth-form-info mb-0"}>
                This is the number listed as your Mobile Phone Number in Account
                Settings. During 2SV challenges, this phone number will be
                included as an option to receive the One Time Password (OTP). To
                change your phone number,{" "}
                <a href="#" className={Styles.commonLink}>
                  click here
                </a>
                .
              </p>
            </div>
          }
        >
          <span
            className={cn(
              "common-link",
              "ms-md-2",
              "d-block",
              "d-md-inline-block"
            )}
          >
            Learn more
            <FontAwesomeIcon
              className={"ms-1 two-step-learn-more"}
              icon={faQuestionCircle}
            />
          </span>
        </Tooltip>
      </div>

      <div className="col-6 col-md-2 col-lg-2 d-flex align-items-end align-items-md-start justify-content-end justify-content-lg-start">
        <a className={Styles.commonLink} href="#">
          Change
        </a>
      </div>

      <div className="d-none d-lg-block col-2 pe-0" />
    </div>
  );
};

export default PhoneNumberAppend;
