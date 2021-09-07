import Modal from "react-bootstrap/Modal";
import ModalTimes from "@client/modules/icon/components/account/ModalTimes";
import React from "react";
import classnames from "classnames";

interface propsDto {
  show: boolean;
  title: string;
  children: React.ReactNode;
  onClose: () => void;
}

const BootstrapModalHOC: React.FC<propsDto> = (props: propsDto) => {
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
      dialogClassName={"m-0 m-sm-2 mx-sm-auto account-modal-dialog"}
      onHide={props.onClose}
    >
      <div className="px-4 position-relative account-modal-hat">
        {headerTemplate("d-none d-sm-block")}

        <div
          className="account-modal-close-button account-modal-hat_close-button"
          onClick={props.onClose}
        >
          <ModalTimes className={"account-modal-close-icon"} />
        </div>
      </div>

      <Modal.Body className={"account-modal-body"}>
        {headerTemplate("d-sm-none")}
        {props.children}
      </Modal.Body>
    </Modal>
  );
};

export default BootstrapModalHOC;
