import Modal from "react-bootstrap/Modal";
import ModalTimes from "@modules/icon/components/account/ModalTimes";
import { Form as RBForm } from "react-bootstrap";
import React from "react";
import { useDialog } from "@modules/account/hooks/useDialog";

const BootstrapModal = function () {
  const dialog = useDialog();

  return (
    <Modal
      show={true}
      onHide={dialog.handleClickOpen}
      centered={true}
      contentClassName={"account-modal-content"}
    >
      <div className="p-4 position-relative account-modal-header">
        <h2 className="account-modal-header-content m-0">Disable 2SV ?</h2>

        <div
          className="account-modal-close-button account-modal-header_close-button"
          onClick={dialog.handleClose}
        >
          <ModalTimes className={"account-modal-close-icon"} />
        </div>
      </div>

      <Modal.Body className={"account-modal-body"}>
        <p className={"account-modal-text"}>
          By disabling Two-Step Verification, OTP will no longer be required to
          Sign-In to your account.
        </p>
        <div>
          <RBForm.Group className={"mb-4"}>
            <input
              name="rememberMe"
              id="rememberMe"
              className="form-checkbox"
              type="checkbox"
              // value={values.rememberMe}
            />

            <RBForm.Label
              className={
                "checkbox-label mb-0 align-items-center d-flex form-label account-modal-text"
              }
              htmlFor={"rememberMe"}
            >
              Also clear my Two-Step Verification settings
            </RBForm.Label>
          </RBForm.Group>
        </div>

        <button
          className="admin-form-control form-button d-inline-block w-auto"
          onClick={dialog.handleClose}
        >
          disable
        </button>

        <button
          className="form-button form-button__outline d-inline-block w-auto ms-12"
          onClick={dialog.handleClose}
        >
          cancel
        </button>
      </Modal.Body>
    </Modal>
  );
};
