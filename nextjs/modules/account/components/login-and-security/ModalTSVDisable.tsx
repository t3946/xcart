import React from "react";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { Form as RBForm } from "react-bootstrap";

interface propsDto {
  show: boolean;
  onClose: () => void;
  onConfirm: () => void;
  ajaxSending: boolean;
}

const ModalTSVDisable: React.FC<any> = function (props: propsDto) {
  const [isConfirm, setIsConfirm] = React.useState(false);
  const confirmRef = React.useRef<HTMLInputElement>();

  return (
    <BootstrapDialogHOC
      show={props.show}
      title={"Disable 2SV ?"}
      onClose={props.onClose}
    >
      <p className={"account-modal-text"}>
        By disabling Two-Step Verification, OTP will no longer be required to
        Sign-In to your account.
      </p>

      <div>
        <RBForm.Group className={"mb-4"}>
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
          className="admin-form-control form-button d-inline-block w-auto"
          onClick={() => isConfirm && props.onConfirm()}
          disabled={props.ajaxSending || !isConfirm}
        >
          disable
        </button>

        <button
          className="form-button form-button__outline d-inline-block w-auto ms-12"
          onClick={props.onClose}
        >
          cancel
        </button>
      </div>
    </BootstrapDialogHOC>
  );
};

export default ModalTSVDisable;
