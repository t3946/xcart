import React from "react";
import InnerPage from "@client/modules/account/components/shared/InnerPage";
import { Form as RBForm } from "react-bootstrap";
import { disableAction } from "@client/jsx/redux/actions/account-actions/TSVActions";
import { userSetAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import { route } from "@client/jsx/utils/AppData";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons/faArrowLeft";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";

const TSVDisable: React.FC<any> = function () {
  const [isConfirm, setIsConfirm] = React.useState(false);
  const confirmRef = React.useRef<HTMLInputElement>();
  const [isDisableTsvSending, setIsDisableTsvSending] = React.useState(false);
  const dispatch = useDispatch();
  const history = useHistory();

  function disableTSVHandler() {
    setIsDisableTsvSending(true);

    dispatch(
      disableAction({
        success(res) {
          setIsDisableTsvSending(false);
          dispatch(userSetAction(res.user));
          history.push(route("account:two-step-verification-settings"));
        },
      })
    );
  }

  return (
    <>
      <div className="row back-button-container">
        <div className="d-flex col">
          <button
            className={"form-button form-button__outline rounded-0 w-auto"}
            onClick={() =>
              history.push(route("account:two-step-verification-settings"))
            }
          >
            <FontAwesomeIcon icon={faArrowLeft} className={"me-3"} />
            <span>back</span>
          </button>
        </div>
      </div>

      <InnerPage header="Disable 2SV ?">
        <div className={"px-10 px-lg-0"}>
          <p className={"m-0"}>
            By disabling Two-Step Verification, OTP will no longer be required
            to Sign-In to your account.
          </p>

          <RBForm.Group className={"mb-4 mt-20"}>
            <input
              ref={confirmRef}
              id="confirmDisableTSVField"
              className="form-checkbox"
              type="checkbox"
              onChange={() => setIsConfirm(confirmRef.current.checked)}
            />

            <RBForm.Label
              className={
                "checkbox-label mb-0 align-items-center d-flex form-label account-modal-text"
              }
              htmlFor={"confirmDisableTSVField"}
            >
              Also clear my Two-Step Verification settings
            </RBForm.Label>
          </RBForm.Group>
        </div>
        <div className={"text-center text-md-start"}>
          <button
            className="admin-form-control form-button d-inline-block"
            onClick={() => isConfirm && disableTSVHandler()}
            disabled={isDisableTsvSending || !isConfirm}
          >
            disable
          </button>

          <button
            className="form-button form-button__outline d-inline-block mt-14 mt-lg-0"
            onClick={() =>
              history.push(route("account:two-step-verification-settings"))
            }
          >
            cancel
          </button>
        </div>
      </InnerPage>
    </>
  );
};

export default TSVDisable;
