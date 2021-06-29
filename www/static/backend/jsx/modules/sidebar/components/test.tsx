import React from "react";

const Test: React.FC<any> = function () {
  function closeModal() {
    modal.style.display = "none";
  }

  function closeModalButtonTemplate() {
    return <i onClick={closeModal}>X</i>;
  }

  function headerTemplate() {
    return (
      <div className="modal-header">header {closeModalButtonTemplate()}</div>
    );
  }

  function bodyTemplate() {
    return <div className="modal-body">body</div>;
  }

  function footerTemplate() {
    return <div className="modal-footer">footer</div>;
  }

  return (
    <div className="modal">
      {headerTemplate()}
      {bodyTemplate()}
      {footerTemplate()}
    </div>
  );
};

export default Test;
