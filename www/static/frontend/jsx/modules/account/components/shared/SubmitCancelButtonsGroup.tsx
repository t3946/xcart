import React from "react";
import classnames from "classnames";

interface PropsInterfaced {
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
}

const SubmitCancelButtonsGroup: React.FC<PropsInterfaced> = function ({
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
}) {
  const classes = {
    submitButton: ["form-button", submitAdvancedClasses, buttonAdvancedClasses],
    cancelButton: [
      "form-button form-button__outline mt-14 mt-md-0 ms-md-12",
      cancelAdvancedClasses,
      buttonAdvancedClasses,
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
