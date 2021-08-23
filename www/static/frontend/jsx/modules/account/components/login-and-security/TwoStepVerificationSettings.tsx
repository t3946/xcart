import React from "react";
import { OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";

const TwoStepVerificationSettings = (): any => {
  return (
    <>
      <div className="account-page_header text-center text-lg-start">
        <h1>Two-Step Verification (2SV) Settings</h1>

        <div className="row">
          <div className="col-12 col-md-6">
            <b className="d-block two-step-status-caption">
              Two-Step Verification
            </b>
            <span className={"two-step-status-indicator"}>Enabled</span>
          </div>

          <div className="col-12 col-md-6 d-flex justify-content-end">
            <button className="form-button form-button__outline w-auto">
              disable
            </button>
          </div>
        </div>
      </div>

      <div className="content-panel">
        <div className="row two-step-row__bordered mx-0 pb-2 mb-2">
          <div className="col-12 px-0">
            <h3 className={"mb-0 content-h3"}>Preferred method</h3>
          </div>
        </div>

        <div className="row two-step-row__bordered mx-0 pb-2 mb-2">
          <div className="col-3 ps-0">
            Authenticator App
            <br />5 apps enrolled
          </div>

          <div className="col-5">
            <a href="#">Add new app</a>
          </div>

          <div className="col-2">
            <a href="#">Change</a>
          </div>

          <div className="col-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered mx-0 pb-2 mb-2 mt-20">
          <div className="col-12 px-0">
            <h3 className={"mb-0 content-h3"}>Backup methods</h3>
          </div>
        </div>

        <div className="row two-step-row__bordered mx-0 pb-2 mb-2">
          <div className="col-3 ps-0">
            +79195153333
            <br />
            Sent by text message
          </div>

          <div className="col-5">
            Phone number
            <OverlayTrigger
              placement="top"
              delay={{ show: 250, hide: 1000 }}
              overlay={
                <Tooltip
                  id="tooltip-details"
                  className={"common-tooltip common-tooltip__login-form"}
                >
                  <h2 className={"common-tooltip-header"}>
                    <b>Your phone number</b>
                  </h2>

                  <p className={"text-align--left auth-form-info mb-0"}>
                    This is the number listed as your Mobile Phone Number in
                    Account Settings. During 2SV challenges, this phone number
                    will be included as an option to receive the One Time
                    Password (OTP). To change your phone number,{" "}
                    <a href="#" className={"common-link"}>
                      click here
                    </a>
                    .
                  </p>
                </Tooltip>
              }
            >
              <span className={"common-link ms-2"}>
                Learn more
                <FontAwesomeIcon
                  className={"ms-1 two-step-learn-more"}
                  icon={faQuestionCircle}
                />
              </span>
            </OverlayTrigger>
          </div>

          <div className="col-2">
            <a href="#">Change</a>
          </div>

          <div className="col-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered mx-0 pb-2 mb-2">
          <div className="col-3 ps-0">
            +79195153333
            <br />
            Sent by text message
          </div>

          <div className="col-5" />

          <div className="col-2">
            <a href="#">Change</a>
          </div>

          <div className="col-2 pe-0">
            <a href="#" className="common-button">
              Remove
            </a>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <h3 className={"content-h3 two-step_otp-header"}>
              Devices that suppress OTP
            </h3>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <p className={"two-step-info"}>
              You may suppress future OTP challenges by selecting "Don't require
              OTP on this browser". As long as the OTP suppression cookie is
              present, a Sign-In from that browser or application will only
              require a password. (Note: This option is enabled separately for
              each browser that you use.)
            </p>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <p className={"two-step-info"}>
              To make sure your account is protected, some actions like changing
              your account security settings, may still require you to enter an
              OTP
            </p>
          </div>
        </div>

        <div className="row mb-4">
          <div className="col-6 d-lg-flex align-items-center">
            <b>You have 33 devices where OTP is suppressed</b>
          </div>

          <div className="col-6 d-md-flex justify-content-end">
            <button className="form-button form-button__outline form-button__theme-grey w-auto px-3">
              Require OTP on all devices
            </button>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <h3 className={"content-h3"}>
              Setting an app as your preferred method
            </h3>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <p className={"two-step-info"}>
              If you want to generate one time passwords from an app instead of
              having them sent to your phone, you'll need to clear your two-step
              verification settings. To do so, tap or click disable, then check
              the box next to "Also clear my Two-Step Verification settings" on
              the window that appears. Lastly, re-enable two-step verification
              using your authenticator app as your preferred method.
            </p>
          </div>
        </div>

        <div className="row">
          <div className="col-12">
            <a href="#">Get help with Two-Step Verification</a>
          </div>
        </div>
      </div>

      <div className="account-page_footer text-center text-lg-start">
        footer
      </div>
    </>
  );
};

export default TwoStepVerificationSettings;
