import React from "react";
import { logoutAction } from "@redux/actions/account-actions/AutorizationActions";
import { userClearAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import cn from "classnames";

interface IProps {
  onClick?: () => void;
  classes?: any;
}

const LogoutButton: React.FC<IProps> = function (props: IProps) {
  const dispatch = useDispatch();
  const router = useRouter();
  const classes = {
    button: [
      "sidebar-menu-item",
      "sidebar-menu_top-level-item",
      "text-start",
      "w-100",
      "sidebar-menu-item__logout",
      props.classes,
    ],
  };

  function logout() {
    HideAllMenu(dispatch);

    dispatch(
      logoutAction({
        success() {
          dispatch(userClearAction());
          router.push("/login");
        },
        error() {
          dispatch(userClearAction());
          router.push("/login");
        },
      })
    );

    props.onClick && props.onClick();
  }

  return (
    <button className={cn(classes.button)} onClick={logout}>
      Log out
    </button>
  );
};

export default LogoutButton;
