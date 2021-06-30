import React from "react";
import { Dropdown } from "react-bootstrap";
import { RoundedCornerIcon } from "@admin/icons/rounded-corner";
import classNames from "classnames";
import $ from "jquery";
import DropPopoverMenuDto from "@admin/modules/common/components/drop-popover-menu/ts/DropPopoverMenuDto";

const CustomToggle = React.forwardRef((props: any, ref: any) => {
  return (
    <div
      ref={ref}
      onClick={(e) => {
        e.preventDefault();
        props.onClick(e);
      }}
      className="d-flex align-items-center pointer pl-3 pr-3"
    >
      <div className="flex-grow-1">{props.children}</div>

      <RoundedCornerIcon
        className="drop-down-icon ml-2.5 drop-down-icon__down"
        color="#000000"
      />
    </div>
  );
});

const CustomMenu = React.forwardRef((props: any, ref: any) => {
  const { children, style, className, "aria-labelledby": labeledBy } = props;

  return (
    <div
      ref={ref}
      style={style}
      className={className}
      aria-labelledby={labeledBy}
    >
      {children}
    </div>
  );
});

const DropPopoverMenu: React.FC<DropPopoverMenuDto> = function (
  props: DropPopoverMenuDto
): any {
  //затемнить экран, когда открыто выпадающее меню и скрывать когда закрыто
  function toggleBackground(expanded) {
    let $background = $(".dropdown-background");

    if (!$background.length) {
      $background = $('<div class="dropdown-background">');
      $background.appendTo(document.body);
    }

    const duration = 250;

    if (expanded) {
      $(document.body).css("overflow", "hidden");
      $background.fadeIn(duration);
    } else {
      $(document.body).css("overflow", "");
      $background.fadeOut(duration);
    }
  }

  return (
    <Dropdown
      onToggle={(e) => toggleBackground(e)}
      onSelect={(eventKey, event: any) => {
        const value = event.target.dataset.value;

        props.onSelect(value);
      }}
    >
      <Dropdown.Toggle as={CustomToggle} variant="success" id="dropdown-basic">
        {props.button}
      </Dropdown.Toggle>

      <Dropdown.Menu
        as={CustomMenu}
        className={classNames([
          "dropdown-menu__popover",
          props.menuClasses,
          "user-select-none",
        ])}
      >
        {props.menu}
      </Dropdown.Menu>
    </Dropdown>
  );
};

export default DropPopoverMenu;
