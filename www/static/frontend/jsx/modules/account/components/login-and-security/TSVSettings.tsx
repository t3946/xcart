import React from "react";
import { OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import { useSelector } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import { useHistory, NavLink } from "react-router-dom";
import { disableAction } from "@client/jsx/redux/actions/account-actions/TSVActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useDialog } from "@client/modules/account/hooks/useDialog";
import ModalTSVDisable from "@client/modules/account/components/login-and-security/ModalTSVDisable";
import InnerPage from "@client/jsx/modules/account/components/shared/InnerPage";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import StoreInterface from "@client/modules/account/ts/types/store.type";

const TSVSettings = (): any => {
  const breakpoint = useBreakpoint();
  const disableTSVModal = useDialog();
  const user = useSelector((e: StoreInterface) => e.user);
  const history = useHistory();
  const dispatch = useDispatch();
  const [isDisableTsvSending, setIsDisableTsvSending] = React.useState(false);

  useSelector((e: StoreInterface) => e.main.breakpoint);

  if (user === null) {
    history.push("/account/login");
  }

  function tsvCountTemplate() {
    if (user.tsv.count) {
      return <div>{user.tsv.count} app(s) enrolled</div>;
    }
  }

  function disableTSVHandler() {
    setIsDisableTsvSending(true);
    dispatch(
      disableAction({
        success(res) {
          disableTSVModal.handleClose();
          setIsDisableTsvSending(false);
          dispatch(userSetAction(res.user));
        },
      })
    );
  }

  function disableTSVButtonTemplate() {
    const className =
      "form-button form-button__outline w-100 w-sm-auto form-button__micro";

    return breakpoint({
      xs: (
        <NavLink
          className={className}
          to={route("account:two-step-verification-settings-disable")}
          exact={true}
        >
          disable
        </NavLink>
      ),
      lg: (
        <button className={className} onClick={disableTSVModal.handleClickOpen}>
          disable
        </button>
      ),
    });
  }

  function disableTSVTemplate() {
    if (user.tsv.count === 0) {
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
        bodyClasses={"content-panel"}
      >
        <div className="row two-step-row__bordered two-step-row__header mx-0 pb-lg-2 lg-2">
          <div className="col-12 px-lg-0">
            <h3 className={"mb-0 content-h3 tsv-settings-box-header"}>
              Preferred method
            </h3>
          </div>
        </div>

        <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
          <div className="col-12 col-lg-3 ps-lg-0 mb-3 mb-lg-0">
            Authenticator App
            {tsvCountTemplate()}
          </div>

          <div className="col-6 col-lg-5">
            <NavLink
              className={"common-link"}
              exact={true}
              to={route("account:two-step-verification-add-new")}
            >
              Add new app
            </NavLink>
          </div>

          <div className="col-6 col-lg-2 text-end text-lg-start">
            <NavLink
              className={"common-link"}
              exact={true}
              to={route(
                "account:two-step-verification-settings-preferred-method"
              )}
            >
              Change
            </NavLink>
          </div>

          <div className="d-none d-lg-block col-lg-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered two-step-row__header mx-0 pb-lg-2 lg-2 mt-lg-20">
          <div className="col-12 px-lg-0">
            <h3 className={"mb-0 content-h3 tsv-settings-box-header"}>
              Backup methods
            </h3>
          </div>
        </div>

        <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
          <div className="col-12 col-lg-3 ps-lg-0 mb-3 mb-lg-0">
            +79195153333
            <br />
            Sent by text message
          </div>

          <div className="col-6 col-lg-5">
            <span className="d-block d-lg-inline-block">Phone number</span>

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
              <span className={"common-link ms-lg-2 d-block d-lg-inline-block"}>
                Learn more
                <FontAwesomeIcon
                  className={"ms-1 two-step-learn-more"}
                  icon={faQuestionCircle}
                />
              </span>
            </OverlayTrigger>
          </div>

          <div className="col-6 col-lg-2 d-flex d-lg-block align-items-end justify-content-end">
            <a href="#">Change</a>
          </div>

          <div className="d-none d-lg-block col-2 pe-0" />
        </div>

        <div className="row two-step-row__bordered mx-0 pb-lg-2 mb-lg-2 tsv-settings-box-content">
          <div className="col-12 col-lg-3 ps-lg-0">
            +79195153333
            <br />
            Sent by text message
          </div>

          <div className="d-none d-lg-block col-5" />

          <div className="col-6 col-lg-2 order-1 order-lg-0 text-end text-lg-start">
            <a href="#">Change</a>
          </div>

          <div className="col-6 col-lg-2 pe-lg-0">
            <a href="#" className="common-button">
              Remove
            </a>
          </div>
        </div>

        <div className="row m-0">
          <h3
            className={
              "content-h3 two-step_otp-header otp-header tsv-settings-box-header"
            }
          >
            <div className="col-12">Devices that suppress OTP</div>
          </h3>
        </div>

        <div className="py-2 py-lg-0">
          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <p className={"two-step-info"}>
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
              <p className={"two-step-info"}>
                To make sure your account is protected, some actions like
                changing your account security settings, may still require you
                to enter an OTP
              </p>
            </div>
          </div>

          <div className="row mx-0 mb-4">
            <div className="col-12 col-lg-6 d-lg-flex align-items-center ps-lg-0 mb-14 mb-md-20 mb-lg-0">
              <b>You have 33 devices where OTP is suppressed</b>
            </div>

            <div className="col-12 col-lg-6 d-md-flex justify-content-lg-end pe-lg-0">
              <button className="form-button form-button__theme-dark-grey w-100 w-md-auto px-3">
                Require OTP on all devices
              </button>
            </div>
          </div>

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <h3 className={"content-h3"}>
                Setting an app as your preferred method
              </h3>
            </div>
          </div>

          <div className="row mx-0">
            <div className="col-12 px-lg-0">
              <p className={"two-step-info"}>
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
              <a href="#">Get help with Two-Step Verification</a>
            </div>
          </div>
        </div>
      </InnerPage>
    </>
  );
};

export default TSVSettings;
