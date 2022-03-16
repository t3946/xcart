import React from "react";
import SVGUserIcon from "@client/modules/icon/components/account/user/User";
import classnames from "classnames";
import Styles from "@client/jsx/modules/account/components/hat/LoginButton/LoginButton.module.scss";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

interface IProps {
  className?: any;
}

const UserIcon: React.FC<IProps> = (props) => {
  const user = useSelectorAccount((e) => e.user);
  const avatarUrl = user?.avatar_image;

  if (!user || !avatarUrl) {
    return (
      <SVGUserIcon className={classnames(Styles.userIcon, props.className)} />
    );
  }

  return (
    <img
      className={classnames(Styles.userAvatar, props.className)}
      src={avatarUrl}
      alt="avatar"
    />
  );
};

export default UserIcon;
