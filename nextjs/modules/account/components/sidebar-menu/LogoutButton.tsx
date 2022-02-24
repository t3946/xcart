import React from "react";
import { logoutAction } from "@redux/actions/account-actions/AutorizationActions";
import { userClearAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import { useRouter } from "next/router";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import cn from "classnames";
import { emptyAction } from "@redux/actions/CartActions";
import Styles from "@modules/account/components/sidebar-menu/LogoutButton.module.scss";

interface IProps {
  onClick?: () => void;
  classes?: any;
}

const LogoutButton: React.FC<IProps> = function (props: IProps) {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const router = useRouter();
  const classes = {
    button: [Styles.button, "text-start", "w-100", props.classes],
  };

  function logout() {
    HideAllMenu(dispatch);

    dispatch(
      logoutAction({
        success() {
          dispatch(userClearAction());
          router.push("/login");
          dispatch(emptyAction());
        },
        error() {
          dispatch(userClearAction());
          router.push("/login");
        },
      })
    );

    props.onClick && props.onClick();
  }
  if (user) {
    return (
      <button className={cn(classes.button)} onClick={logout}>
        Log out
      </button>
    );
  } else {
    return (
      <button
        className={cn(classes.button)}
        onClick={() => router.push("/login")}
      >
        Log in
      </button>
    );
  }
};

export default LogoutButton;
