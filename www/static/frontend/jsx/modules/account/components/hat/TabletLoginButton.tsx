import classNames from "classnames";
import React from "react";
import SidebarMenu from "../sidebar-menu/SideBarMenu";
import { Dropdown } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import { setTabletMenuIsVisible } from "../../../../redux/actions/account-actions/MenuActions";

const TabletLoginButton: React.FC<any> = () => {
  const dispatch = useDispatch();
  const text = appData.user ? appData.user.name : "log in";

  const CustomToggle = React.forwardRef((props, ref) => {
    const { onClick } = props;

    return (
      <div
        onClick={(e) => {
          onClick(e);
        }}
        ref={ref}
        className={classNames(
          "navigation-login-button d-none d-md-flex navigation-login-button__tablet align-items-center justify-content-between",
          {
            "navigation-login-button__active": props["aria-expanded"],
          }
        )}
      >
        {text}

        <i
          className={classNames(
            "navigation-login-button-arrow arrow-rotatable",
            {
              "arrow-rotatable__rotated": props["aria-expanded"],
            }
          )}
        />
      </div>
    );
  });

  const CustomMenu = React.forwardRef(
    ({ children, style, className, "aria-labelledby": labeledBy }, ref) => {
      return (
        <div
          ref={ref}
          className={classNames(
            className,
            "account-hat-dropdown-menu col-12 p-0 rounded-0"
          )}
          aria-labelledby={labeledBy}
        >
          <SidebarMenu />
        </div>
      );
    }
  );

  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );

  return (
    <Dropdown
      show={isTabletMenuVisible}
      onToggle={(prop) => {
        console.log("onToggle", prop);
        dispatch(setTabletMenuIsVisible(prop));
      }}
      onClick={(e) => e.stopPropagation()}
    >
      <Dropdown.Toggle id="dropdown-basic" as={CustomToggle} />
      <Dropdown.Menu as={CustomMenu} />
    </Dropdown>
  );
};

export default TabletLoginButton;
