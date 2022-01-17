import React from "react";
import { OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import { useSelector } from "react-redux";
import Link from "next/link";
import { disableAction } from "@redux/actions/account-actions/TSVActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useDialog } from "@modules/account/hooks/useDialog";
import ModalTSVDisable from "@modules/account/components/login-and-security/ModalTSVDisable";
import InnerPage from "@modules/account/components/shared/InnerPage";
import StoreInterface from "@modules/account/ts/types/store.type";
import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import cn from "classnames";
import { AxiosResponse } from "axios";
import SuppressedDevices from "@modules/account/components/login-and-security/tsv/SuppressedDevices";

import Styles from "@modules/account/components/login-and-security/TSVSettings.module.scss";

const TSVSettings = (): any => {
  const disableTSVModal = useDialog();
  const user = useSelector((e: StoreInterface) => e.user);
  const dispatch = useDispatch();
  const [isDisableTsvSending, setIsDisableTsvSending] = React.useState(false);

  useSelector((e: StoreInterface) => e.main.breakpoint);

  function tsvCountTemplate() {
    if (user.tsv_count) {
      return <div>{user.tsv_count} app(s) enrolled</div>;
    }
  }

  function disableTSVHandler() {
    setIsDisableTsvSending(true);
    dispatch(
      disableAction({
        success(res: AxiosResponse) {
          disableTSVModal.handleClose();
          setIsDisableTsvSending(false);
          dispatch(userSetAction(res.data.user));
        },
      })
    );
  }

  function disableTSVButtonTemplate() {
    const classes = {
      base: [
        "form-button",
        "form-button__outline",
        "w-100",
        "w-md-auto",
        "w-sm-auto",
        "px-md-5",
        Styles.button_disable,
        "form-button__micro",
        "fw-bold",
      ],
      link: ["d-flex", "align-items-center", "d-lg-none"],
      button: ["d-none", "d-lg-block"],
    };

    return (
      <>
        <Link
          href={"/login-and-security/two-step-verification-settings-disable"}
        >
          <span className={cn(classes.base, classes.link)}>disable</span>
        </Link>

        <button
          className={cn(classes.base, classes.button)}
          onClick={disableTSVModal.handleClickOpen}
        >
          disable
        </button>
      </>
    );
  }

  function disableTSVTemplate() {
    if (user.tsv_count === 0) {
      return;
    }

    return (
      <div className="row mt-3 mt-3">
        <div className="col-12 col-sm-6 mb-3 mb-sm-0">
          <b className="d-block two-step-status-caption">
            Two-Step Verification
          </b>

          <span className={"two-step-status-indicator"}>Enabled</span>
        </div>

        <div className="col-12 col-sm-6 d-flex justify-content-end">
          {disableTSVButtonTemplate()}
        </div>
      </div>
    );
  }

  if (user === null) {
    return null;
  }

  return (
    <>
      <ModalTSVDisable
        show={disableTSVModal.open}
        onClose={disableTSVModal.handleClose}
        onConfirm={disableTSVHandler}
        ajaxSending={isDisableTsvSending}
      />

      <InnerPage
        header={"Two-Step Verification (2SV) Settings"}
        hat={<>{disableTSVTemplate()}</>}
        bodyClasses={[
          "content-panel",
          StylesLoginAndSecurity.pageBody,
          Styles.pageBody,
        ]}
      >
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
              Preferred method
            </h3>
          </div>
        </div>

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
            {tsvCountTemplate()}
          </div>

          <div className="col-6 col-md-4 col-lg-5 d-flex justify-content-md-center">
            <Link
              exact={true}
              href={"/login-and-security/two-step-verification-add-new"}
            >
              <span className={Styles.commonLink}>Add new app</span>
            </Link>
          </div>

          <div className="col-6 col-md-2 col-lg-2 d-flex justify-content-end justify-content-lg-start">
            <Link
              exact={true}
              href={
                "/login-and-security/two-step-verification-settings-preferred-method"
              }
            >
              <span className={Styles.commonLink}>Change</span>
            </Link>
          </div>

          <div className="d-none d-lg-block col-lg-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered two-step-row__header mx-0 pb-lg-2 lg-2 mt-lg-20">
          <div className="col-12 px-lg-0">
            <h3
              className={cn(
                Styles.caption,
                "mb-0",
                "content-h3",
                "tsv-settings-box-header"
              )}
            >
              Backup methods
            </h3>
          </div>
        </div>

        <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
          <div
            className={cn(
              Styles.text,
              "col-12 col-md-3 pe-md-0 ps-lg-0 mb-12 mb-lg-0"
            )}
          >
            +79195153333
            <br />
            Sent by text message
          </div>

          <div className="col-6 col-md-4 col-lg-5 text-md-center">
            <span className="d-block d-md-inline-block">Phone number</span>

            <OverlayTrigger
              placement="top"
              trigger="click"
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
                    <a href="#" className={Styles.commonLink}>
                      click here
                    </a>
                    .
                  </p>
                </Tooltip>
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
            </OverlayTrigger>
          </div>

          <div className="col-6 col-md-2 col-lg-2 d-flex align-items-end align-items-md-start justify-content-end justify-content-lg-start">
            <a className={Styles.commonLink} href="#">
              Change
            </a>
          </div>

          <div className="d-none d-lg-block col-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
          <div
            className={cn(Styles.text, "col-12 col-md-3 pe-md-0 ps-lg-0 mb-12")}
          >
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

        <div className="row m-0 mb-lg-2">
          <h3
            className={
              "content-h3 two-step_otp-header otp-header tsv-settings-box-header p-lg-0 mb-lg-10"
            }
          >
            <div className={cn(Styles.caption, "col-12")}>
              Devices that suppress OTP
            </div>
          </h3>
        </div>

        <div className="py-2 py-lg-0">
          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <p className={"two-step-info mb-md-1 mb-lg-4"}>
                You may suppress future OTP challenges by selecting "Don't
                require OTP on this browser". As long as the OTP suppression
                cookie is present, a Sign-In from that browser or application
                will only require a password. (Note: This option is enabled
                separately for each browser that you use.)
              </p>
            </div>
          </div>

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <p className={"two-step-info mb-md-20 mb-lg-14"}>
                To make sure your account is protected, some actions like
                changing your account security settings, may still require you
                to enter an OTP
              </p>
            </div>
          </div>

          <SuppressedDevices />

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <h3 className={cn(Styles.caption, "content-h3")}>
                Setting an app as your preferred method
              </h3>
            </div>
          </div>

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <p className={"two-step-info mb-md-2"}>
                If you want to generate one time passwords from an app instead
                of having them sent to your phone, you'll need to clear your
                two-step verification settings. To do so, tap or click disable,
                then check the box next to "Also clear my Two-Step Verification
                settings" on the window that appears. Lastly, re-enable two-step
                verification using your authenticator app as your preferred
                method.
              </p>
            </div>
          </div>

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <a className={Styles.commonLink} href="#">
                Get help with Two-Step Verification
              </a>
            </div>
          </div>
        </div>
      </InnerPage>
    </>
  );
};

export default TSVSettings;
