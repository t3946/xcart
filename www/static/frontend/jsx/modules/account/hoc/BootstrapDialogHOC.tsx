import Modal from "react-bootstrap/Modal";
import ModalTimes from "@client/modules/icon/components/account/ModalTimes";
import React from "react";

interface propsDto {
  show: boolean;
  title: string;
  children: React.ReactNode;
  onClose: () => void;
}

const BootstrapModalHOC: React.FC<any> = function (props: propsDto) {

  return (
    <Modal
      show={props.show}
      centered={true}
      contentClassName={"account-modal-content"}
    >
      <div className="p-4 position-relative account-modal-header">
        <h2 className="account-modal-header-content m-0">{props.title}</h2>

        <div
          className="account-modal-close-button account-modal-header_close-button"
          onClick={props.onClose}
        >
          <ModalTimes className={"account-modal-close-icon"} />
        </div>
      </div>

      <Modal.Body className={"account-modal-body"}>{props.children}</Modal.Body>
    </Modal>
  );
};

export default BootstrapModalHOC;
