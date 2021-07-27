import React from "react";
import classNames from "classnames";
import { Dropdown } from "react-bootstrap";
import SidebarMenu from "../sidebar-menu/SideBarMenu";

const LoginButton = (props) => {
  function mobileTemplate() {
    const classes = [
      "navigation-login-button d-md-none common-icon d-flex align-items-center",
    ];

    if (appData.user) {
      classes.push("navigation-login-button__logged");
    } else {
      classes.push("navigation-login-button__not-logged");
    }

    return <i className={classNames(classes)} />;
  }

  function tabletTemplate() {
    const text = appData.user ? appData.user.name : "log in";

    const CustomToggle = React.forwardRef((props, ref) => {
      const { onClick } = props;

      return (
        <div
          onClick={(e) => {
            onClick(e);
          }}
          ref={ref}
          className="navigation-login-button d-none d-md-flex navigation-login-button__tablet align-items-center justify-content-between"
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

    return (
      <Dropdown>
        <Dropdown.Toggle id="dropdown-basic" as={CustomToggle} />

        <Dropdown.Menu as={CustomMenu}>
          <Dropdown.Item href="#/action-1">Action</Dropdown.Item>
          <Dropdown.Item href="#/action-2">Another action</Dropdown.Item>
          <Dropdown.Item href="#/action-3">Something else</Dropdown.Item>
        </Dropdown.Menu>
      </Dropdown>
    );
  }

  return (
    <React.Fragment>
      {mobileTemplate()}
      {tabletTemplate()}
    </React.Fragment>
  );
};

export default LoginButton;
