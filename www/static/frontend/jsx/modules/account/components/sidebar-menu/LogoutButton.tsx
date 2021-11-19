import React from "react";
import { logoutAction } from "@client/jsx/redux/actions/account-actions/AutorizationActions";
import { userClearAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { useHistory } from "react-router-dom";
import { useDispatch } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";

interface IProps {
  onClick?: () => void;
}

const LogoutButton: React.FC<IProps> = function (
  props: IProps
) {
  const history = useHistory();
  const dispatch = useDispatch();

  function logout() {
    HideAllMenu(dispatch);

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
