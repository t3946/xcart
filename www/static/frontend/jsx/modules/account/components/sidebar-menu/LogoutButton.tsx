import React from "react";
import { logoutAction } from "@client/jsx/redux/actions/account-actions/AutorizationActions";
import { userClearAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import cn from "classnames";

import Styles from "@client/jsx/modules/account/components/sidebar-menu/LogoutButton.module.scss";

interface IProps {
  onClick?: () => void;
}

const LogoutButton: React.FC<IProps> = function (props: IProps) {
  const history = useHistory();
  const dispatch = useDispatch();

  function logout() {
    HideAllMenu(dispatch);

    dispatch(
      logoutAction({
        callback() {
          dispatch(userClearAction());

          if (history) {
            history.push("/account/login");
          }
        },
      })
    );

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
