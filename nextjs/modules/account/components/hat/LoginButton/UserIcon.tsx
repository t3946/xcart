import React from "react";
import SVGUserIcon from "@modules/icon/components/account/user/User";
import classnames from "classnames";
import Styles from "@modules/account/components/hat/LoginButton/LoginButton.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import getStoreUrl from "@utils/getStoreUrl";

const UserIcon: React.FC = () => {
  const user = useSelectorAccount((e) => e.user);

  if (!user || !user.avatar_image) {
    return (
      <SVGUserIcon className={classnames(Styles.userIcon, "flex-shrink-0")} />
    );
  }

  return (
    <img
      className={classnames(Styles.userAvatar, "flex-shrink-0")}
      src={getStoreUrl(user.avatar_image)}
      alt="avatar"
    />
  );
};

export default UserIcon;
