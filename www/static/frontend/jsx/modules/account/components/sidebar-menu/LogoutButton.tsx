import React from "react";
import { logoutAction } from "@client/jsx/redux/actions/account-actions/AutorizationActions";
import { userClearAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import { route } from "@client/jsx/utils/AppData";

interface PropsInterface {
  onClick?: () => void;
}

const LogoutButton: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  const history = useHistory();
  const dispatch = useDispatch();

  function logout() {
    dispatch(
      logoutAction({
        callback() {
          dispatch(userClearAction());

          if (history) {
            history.push(route("account:login"));
          }
        },
      })
    );

    props.onClick && props.onClick();
  }

  return (
    <button
      className={
        "sidebar-menu-item sidebar-menu_top-level-item text-start w-100 sidebar-menu-item__logout"
      }
      onClick={logout}
    >
      Log out
    </button>
  );
};

export default LogoutButton;
