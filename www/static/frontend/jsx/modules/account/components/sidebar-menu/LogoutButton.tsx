import React from "react";
import {useDispatch} from "react-redux";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import cn from "classnames";
import Styles from "@client/jsx/modules/account/components/sidebar-menu/LogoutButton.module.scss";
import axios from "axios";

interface IProps {
  onClick?: () => void;
}

const LogoutButton: React.FC<IProps> = function (props: IProps) {
  const dispatch = useDispatch();

  function logout() {
    HideAllMenu(dispatch);

    axios.get("/api-client/user/logout").then(() => {
      document.location.reload();
    });

    props.onClick && props.onClick();
  }

  return (
    <button
      className={cn(
        "sidebar-menu-item",
        "sidebar-menu_top-level-item",
        "text-start",
        "w-100",
        Styles.logoutButton
      )}
      onClick={logout}
    >
      Log out
    </button>
  );
};

export default LogoutButton;
