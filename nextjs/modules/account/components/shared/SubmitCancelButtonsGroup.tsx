import React from "react";
import classnames from "classnames";

import Styles from "@modules/account/components/shared/SubmitCancelButtonsGroup.module.scss";

interface IProps {
  submitText?: string;
  cancelText?: string;

  submitDisabled?: boolean;
  cancelDisabled?: boolean;
  disabled?: boolean;

  buttonAdvancedClasses?: any;
  submitAdvancedClasses?: any;
  cancelAdvancedClasses?: any;
  groupAdvancedClasses?: any;

  onCancel?: () => void;
  onConfirm?: () => void;
}

const SubmitCancelButtonsGroup: React.FC<IProps> = function ({
  submitText,
  cancelText,
  submitDisabled,
  cancelDisabled,
  disabled,
  submitAdvancedClasses,
  cancelAdvancedClasses,
  buttonAdvancedClasses,
  groupAdvancedClasses,
  onCancel,
  onConfirm,
}) {
  const classes = {
    submitButton: [
      "form-button",
      submitAdvancedClasses,
      buttonAdvancedClasses,
      Styles.button,
    ],
    cancelButton: [
      "form-button fw-bold form-button__outline mt-14 mt-md-0 ms-md-12",
      cancelAdvancedClasses,
      buttonAdvancedClasses,
      Styles.button,
    ],
  };

  if (submitDisabled === undefined) {
    submitDisabled = disabled;
  }

  if (cancelDisabled === undefined) {
    cancelDisabled = disabled;
  }

  function onClickCancel() {
    if (onCancel !== undefined) {
      onCancel();
    }
  }

  return (
    <div className={classnames(groupAdvancedClasses)}>
      <button
        className={classnames(classes.submitButton)}
        type={"submit"}
        disabled={submitDisabled}
        onClick={onConfirm}
      >
        {submitText || "submit"}
      </button>

      {onCancel && (
        <button
          className={classnames(classes.cancelButton)}
          type={"button"}
          disabled={cancelDisabled}
          onClick={onClickCancel}
        >
          {cancelText || "cancel"}
        </button>
      )}
    </div>
  );
};

export default SubmitCancelButtonsGroup;
