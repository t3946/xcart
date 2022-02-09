import React from "react";
import cn from "classnames";
import Link from "next/link";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import LockIcon from "@modules/icon/components/account/lock/Lock";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import DashboardStyles from "@modules/account/components/dashboard/Dashboard.module.scss";
import Styles from "@modules/account/components/dashboard/AccountInfo.module.scss";
import { getMaskedPhone } from "@utils/phoneNumber";

const AccountInfo = () => {
  const classes = {
    rectangularButtonContainer: [
      "w-100",
      DashboardStyles.card,
      Styles.accountInfoCard,
      "justify-content-between",
      "align-items-start",
    ],
  };

  const user = useSelectorAccount((e) => e.user);

  if (!user) {
    return null;
  }

  return (
    <RectangularButton
      classNames={{ container: classes.rectangularButtonContainer }}
      header={
        <div
          className={cn(
            "d-flex",
            "justify-content-between",
            "align-items-start",
            "align-items-md-center",
            "align-items-lg-end",
            "flex-lg-wrap",
            "w-100",
            Styles.userInfoHeader
          )}
        >
          <span className={cn(Styles.userInfoName, "me-lg-2")}>
            {user.name}
          </span>
          <Link href="/login-and-security">
            <a
              className={cn(
                Styles.userInfoLoginAndSecurity,
                "mt-lg-2",
                "mt-xxl-0",
                "d-flex",
                "align-items-center",
                "lh-1"
              )}
            >
              <LockIcon className={"me-1"} />
              My Login & Security
            </a>
          </Link>
        </div>
      }
      body={
        <div className="">
          {user.phone && <>Phone: {getMaskedPhone(user.phone)}</>}
          <br />
          {user.email && <>Email address: {user.email}</>}
        </div>
      }
    />
  );
};

export default AccountInfo;
