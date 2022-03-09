import React from "react";
import SVGUserIcon from "@modules/icon/components/account/user/User";
import classnames from "classnames";
import Styles from "@modules/account/components/hat/LoginButton/LoginButton.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const UserIcon: React.FC = () => {
  const user = useSelectorAccount((e) => e.user);
  const avatarUrl = user?.avatar_image;

  if (!user || !avatarUrl) {
    return (
      <SVGUserIcon className={classnames(Styles.userIcon, "flex-shrink-0")} />
    );
  }

  return (
    <img
      className={classnames(Styles.userAvatar, "flex-shrink-0")}
      src={`/${avatarUrl}`}
      alt="avatar"
    />
  );
};

export default UserIcon;
