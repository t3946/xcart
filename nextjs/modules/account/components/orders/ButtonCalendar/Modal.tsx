import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import CalendarIcon from "@modules/icon/components/common/calendare/calendar";
import StylesButtonCalendar from "@modules/account/components/orders/ButtonCalendar/ButtonCalendar.module.scss";
import Styles from "@modules/account/components/orders/ButtonCalendar/Modal.module.scss";
import React from "react";
import cn from "classnames";

interface IProps {
  dialog: any;
  options: any;
  value: any;
  onSelect: any;
}

const Modal: React.FC<IProps> = function (props: IProps) {
  const { dialog, options, value, onSelect } = props;

  function optionsTemplate() {
    const templates = [];

    for (const i in options) {
      const option = options[i];
      templates.push(
        <div
          className={cn(
            Styles.item,
            { [Styles.item_active]: value.value === option.value },
            "d-flex",
            "align-items-center"
          )}
          onClick={() => {
            onSelect(option);
            dialog.handleClose();
          }}
          key={`option-${i}`}
        >
          {option.label}
        </div>
      );
    }

    return templates;
  }

  return (
    <BootstrapDialogHOC
      show={dialog.open}
      onClose={dialog.handleClose}
      classes={{ body: "px-0" }}
    >
      <div className={cn(Styles.header, "d-flex", "align-items-center")}>
        <CalendarIcon className={StylesButtonCalendar.icon} />
        <span className={"ms-20"}>Time period</span>
      </div>

      {optionsTemplate()}
    </BootstrapDialogHOC>
  );
};

export default Modal;
