import Modal from "react-bootstrap/Modal";
import ModalTimes from "@modules/icon/components/account/ModalTimes";
import React from "react";
import classnames from "classnames";
import cn from "classnames";
import Styles from "@modules/account/hoc/BootstrapDialogHOC.module.scss";
import Arrow from "@modules/icon/components/font-awesome/arrow-left/Solid";

interface BootstrapModalProps {
  show: boolean;
  title: string;
  children: React.ReactNode;
  onClose: () => void;
  classes?: {
    header?: string | string[];
    body?: string | string[];
    modal?: string | string[];
  };
}

const BootstrapModalHOC: React.FC<BootstrapModalProps> = (
  props: BootstrapModalProps
) => {
  function headerTemplate(className: any) {
    return (
      <h2 className={classnames("account-modal-header m-0", className)}>
        {props.title}
      </h2>
    );
  }

  return (
    <Modal
      show={props.show}
      centered={true}
      fullscreen={"lg-down"}
      contentClassName="account-modal-content"
      dialogClassName={classnames(
        "m-0 mx-sm-auto p-lg-0 account-modal-dialog",
        Styles.modal,
        props?.classes?.modal
      )}
      onHide={props.onClose}
    >
      <div
        className={classnames(
          "px-4 position-relative",
          "account-modal-hat",
          Styles.accountModalHat,
          props?.classes?.header
        )}
      >
        {headerTemplate(`d-none d-sm-block`)}

        <div
          className={cn(
            "account-modal-close-button",
            Styles.accountModalHat__closeButton
          )}
          onClick={props.onClose}
        >
          <ModalTimes
            className={"account-modal-close-icon d-none d-sm-block"}
          />
          <button
            className={classnames(
              "d-sm-none",
              "form-button",
              "form-button__outline",
              "d=flex",
              Styles.accountModalHatCloseButton_mobile
            )}
            type="button"
          >
            <Arrow className={Styles.accountModalHatCloseButtonIcon} />
            Back
          </button>
        </div>
      </div>

      <Modal.Body
        className={classnames(
          props?.classes?.body,
          "account-modal-body",
          Styles.modalBody
        )}
      >
        {headerTemplate("d-sm-none")}
        {props.children}
      </Modal.Body>
    </Modal>
  );
};

export default BootstrapModalHOC;
