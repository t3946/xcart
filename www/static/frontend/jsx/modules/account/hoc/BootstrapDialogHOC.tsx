import Modal from "react-bootstrap/Modal";
import ModalTimes from "@client/modules/icon/components/account/ModalTimes";
import React from "react";
import classnames from "classnames";

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
  function headerTemplate(className) {
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
      contentClassName="account-modal-content"
      dialogClassName={classnames(
        "m-0 m-sm-2 mx-sm-auto account-modal-dialog",
        props?.classes?.modal
      )}
      onHide={props.onClose}
    >
      <div
        className={classnames(
          "px-4 position-relative account-modal-hat",
          props?.classes?.header
        )}
      >
        {headerTemplate(`d-none d-sm-block`)}

        <div
          className="account-modal-close-button account-modal-hat_close-button"
          onClick={props.onClose}
        >
          <ModalTimes className={"account-modal-close-icon"} />
        </div>
      </div>

      <Modal.Body
        className={classnames(props?.classes?.body, "account-modal-body")}
      >
        {headerTemplate("d-sm-none")}
        {props.children}
      </Modal.Body>
    </Modal>
  );
};

export default BootstrapModalHOC;
