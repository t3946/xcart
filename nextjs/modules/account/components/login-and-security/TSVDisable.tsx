import React from "react";
import InnerPage from "@modules/account/components/shared/InnerPage";
import { Form as RBForm } from "react-bootstrap";
import { disableAction } from "@redux/actions/account-actions/TSVActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons/faArrowLeft";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";

import StylesLoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";
import {AxiosResponse} from "axios";

const TSVDisable: React.FC<any> = function () {
  const [isConfirm, setIsConfirm] = React.useState(false);
  const confirmRef = React.useRef<HTMLInputElement>();
  const [isDisableTsvSending, setIsDisableTsvSending] = React.useState(false);
  const dispatch = useDispatch();
  const router = useRouter();

  function disableTSVHandler() {
    setIsDisableTsvSending(true);

    dispatch(
      disableAction({
        success(res: AxiosResponse) {
          setIsDisableTsvSending(false);
          dispatch(userSetAction(res.data.user));
          router.push("/login-and-security/two-step-verification-settings");
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
              router.push("/login-and-security/two-step-verification-settings")
            }
          >
            <FontAwesomeIcon icon={faArrowLeft} className={"me-3"} />
            <span>back</span>
          </button>
        </div>
      </div>

      <InnerPage
        header="Disable 2SV ?"
        bodyClasses={StylesLoginAndSecurity.pageBody}
      >
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
              router.push("/login-and-security/two-step-verification-settings")
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
