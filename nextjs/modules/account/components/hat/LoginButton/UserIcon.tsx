import React from "react";
import SVGUserIcon from "@client/modules/icon/components/account/user/User";
import classnames from "classnames";
import Styles from "@client/jsx/modules/account/components/hat/LoginButton/LoginButton.module.scss";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";

const UserIcon: React.FC = () => {
  const user = useSelectorAccount((e) => e.user);
  const avatarUrl = user?.avatar_image;

  if (!user || !avatarUrl) {
    return <SVGUserIcon className={classnames(Styles.userIcon)} />;
  }

  return (
    <img
      className={classnames(Styles.userAvatar)}
      src={avatarUrl}
      alt="avatar"
    />
  );
};

export default UserIcon;
