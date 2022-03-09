import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/ButtonCalendar/ButtonCalendar.module.scss";
import CalendarIcon from "@modules/icon/components/common/calendare/calendar";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { useDialog } from "@modules/account/hooks/useDialog";
import Modal from "@modules/account/components/orders/ButtonCalendar/Modal";

interface IProps {
  className?: any;
  theme?: ETheme;
  disabled?: boolean;
  children?: any;
  options: any;
  value: any;
  onSelect: any;
}

const ButtonCalendar: React.FC<IProps> = function (props: IProps) {
  const { className, theme, disabled, options, value, onSelect } = props;
  const dialog = useDialog();

  return (
    <>
      <Button
        className={className}
        disabled={disabled}
        type={"button"}
        theme={theme || ETheme.themeGrey}
        onClick={dialog.handleClickOpen}
      >
        <CalendarIcon className={Styles.icon} />
        <span className={cn("ms-12", Styles.text)}>{props.children}</span>
      </Button>

      <Modal
        dialog={dialog}
        options={options}
        value={value}
        onSelect={onSelect}
      />
    </>
  );
};

export default ButtonCalendar;
