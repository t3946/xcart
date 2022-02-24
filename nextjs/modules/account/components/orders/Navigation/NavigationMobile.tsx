import React from "react";
import classnames from "classnames";
import DropDown from "@modules/account/hoc/DropDown";
import ArrowIconMobileDesktop from "@modules/icon/components/account/chevron-down/AccountSidebarMobileDesktop";
import Badge from "@modules/account/components/orders/Navigation/Badge";

interface IProps {
  menu: {
    text: string;
    path: string;
    badge?: string | number;
    classes?: any;
  }[];
  className: any;
}

const Navigation: React.FC<IProps> = (props: IProps) => {
  const [active, setActive] = React.useState("0");
  const { menu, className } = props;

  function toggleTemplate(isVisible: any) {
    const { text, badge } = menu[active];
    const classes = {
      toggle: [
        "orders-navigation-toggle",
        "d-flex",
        "align-items-center",
        "justify-content-between",
        { "orders-navigation-toggle_theme_red": !!badge },
      ],
      icon: [
        "d-inline-block",
        "flip",
        {
          flip_flipped: isVisible,
        },
        "orders-navigation-toggle-icon",
      ],
    };

    return (
      <div className={classnames(classes.toggle)}>
        <span className={"d-flex align-items-center"}>
          {text}

          <span className={"d-inline-block"}>
            {badge && <Badge className="ms-20" text={badge} />}
          </span>
        </span>

        <span className={classnames(classes.icon)}>
          <ArrowIconMobileDesktop />
        </span>
      </div>
    );
  }

  function menuTemplate() {
    return menu.map((value, index) => {
      const { text, badge } = value;

      return (
        <span className={"d-flex align-items-center"} key={index}>
          {text}

          <span className={"d-inline-block"}>
            {badge && <Badge className="ms-20" text={badge} />}
          </span>
        </span>
      );
    });
  }

  return (
    <DropDown
      toggle={(isVisible) => toggleTemplate(isVisible)}
      items={menuTemplate()}
      active={active}
      onSelect={(eventKey) => {
        setActive(eventKey);
      }}
      classes={{
        menu: "orders-navigation-menu-mobile orders-navigation__menu-mobile mt-2",
        dropDown: className,
        item: "orders-navigation-menu-mobile-item",
      }}
    />
  );
};

export default Navigation;
