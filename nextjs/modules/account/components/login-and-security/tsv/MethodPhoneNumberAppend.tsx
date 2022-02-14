import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/login-and-security/TSVSettings.module.scss";

const MethodPhoneNumberAppend: React.FC<any> = function () {
  return null;
  return (
    <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
      <div className={cn(Styles.text, "col-12 col-md-3 pe-md-0 ps-lg-0 mb-12")}>
        +79195153333
        <br />
        Sent by text message
      </div>

      <div className="d-none d-lg-block col-5 col-md-3 col-lg-5" />

      <div className="order-1 order-md-0 col-6 col-md-6 col-lg-2 text-end text-lg-start">
        <a className={Styles.commonLink} href="#">
          Change
        </a>
      </div>

      <div className="col-6 col-md-3 col-lg-2 pe-lg-0 d-flex d-lg-block justify-content-md-end">
        <a className={Styles.commonLink} href="#">
          Remove
        </a>
      </div>
    </div>
  );
};

export default MethodPhoneNumberAppend;
