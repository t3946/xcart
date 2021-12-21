import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import cn from "classnames";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const TSVRecovery: React.FC<any> = function () {
  return (
    <InnerPage
      header={"Two-Step Verification Account Recovery"}
      bodyClasses={cn("p-0", StylesLoginAndSecurity.pageBody)}
    >
      <div className={"content-panel"}>
        <p>
          To regain access to your account, you'll need to verify your identity.
          To do so, you'll need to provide a picture (a scan, or a photo) of a
          government-issued identity document. Acceptable forms of
          government-issued identification include:
        </p>

        <ul>
          <li>A state-issued driver license</li>
          <li>A state ID card</li>
          <li>A voter registration card</li>
        </ul>

        <strong>Before uploading, please make sure that:</strong>

        <ul>
          <li>
            Any sensitive information, such as account number or identification
            numbers, are covered, concealed, or removed.
          </li>
          <li>
            Your name and address, as well as the issuing authority (e.g., state
            or country) are clearly visible.
          </li>
        </ul>

        <p>
          The verification process may take 1-2 days to complete. We will send
          an email to <b>albert.einstein@gmail.com</b> once Two Step
          Verification has been disabled. You will then be able to access your
          account, with only your password. You can re-enable Two-Step
          Verification at any time.
        </p>

        <h2>Upload a document</h2>

        <button
          className={"form-button form-button__theme-grey form-button__micro"}
        >
          Choose file
        </button>
      </div>

      <div className={"text-md-center text-lg-start account-page-footer"}>
        <button
          className={"form-button d-md-inline-block tsv-recovery-submit-button"}
        >
          Submit
        </button>
      </div>
    </InnerPage>
  );
};

export default TSVRecovery;
