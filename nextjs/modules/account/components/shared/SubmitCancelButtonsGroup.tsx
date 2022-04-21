import React from "react";
import classnames from "classnames";
import Button, { ETheme } from "@modules/ui/forms/Button";
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
    submitButton: [submitAdvancedClasses, buttonAdvancedClasses, Styles.button],
    cancelButton: [cancelAdvancedClasses, buttonAdvancedClasses, Styles.button, "ms-10"],
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
    <div className={classnames(groupAdvancedClasses, "d-flex")}>
      <Button
        className={classnames(classes.submitButton)}
        type={"submit"}
        disabled={submitDisabled}
        onClick={onConfirm}
      >
        {submitText || "submit"}
      </Button>

      {onCancel && (
        <Button
          className={classnames(classes.cancelButton)}
          type={"button"}
          disabled={cancelDisabled}
          onClick={onClickCancel}
          theme={ETheme.outlined}
        >
          {cancelText || "cancel"}
        </Button>
      )}
    </div>
  );
};

export default SubmitCancelButtonsGroup;
