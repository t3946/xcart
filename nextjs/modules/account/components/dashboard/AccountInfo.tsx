import React from "react";
import cn from "classnames";
import Link from "next/link";
import RectangularButton from "@modules/account/components/common/RectangularButton";
import LockIcon from "@modules/icon/components/account/lock/Lock";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import DashboardStyles from "@modules/account/components/dashboard/Dashboard.module.scss";
import Styles from "@modules/account/components/dashboard/AccountInfo.module.scss";
import { useRouter } from "next/router";

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
  const router = useRouter();

  if (!user) {
    return null;
  }

  if (!user) return <>no user</>;

  return (
    <div className={cn("d-flex", Styles.accountInfo)}>
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
                  "mt-xxl-0"
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
            Phone: {user.phone}
            <br />
            Email address: {user.email}
          </div>
        }
      />
      <RectangularButton
        classNames={{
          container: [
            classes.rectangularButtonContainer,
            Styles.accountInfoCard_Lp,
          ],
        }}
        header={
          <div
            className={cn(
              Styles.lpInfoHeader,
              "d-flex flex-dir-column justify-content-between"
            )}
          >
            <div className={Styles.userInfoName}>Rewards</div>
            <div>
              <div className={Styles.lpInfoCount}>85</div>
              <div>Reta LP</div>
            </div>
          </div>
        }
        body={
          <div
            className={cn(
              "d-flex flex-dir-column justify-content-center",
              Styles.lpInfoRecentActivity
            )}
          >
            <div
              className={cn(
                Styles.lpInfoRecentActivityTitle,
                "mt-md-10",
                "mb-1",
                "mt-lg-1"
              )}
            >
              <b>Recent activity</b>
            </div>
            <div>
              <div>
                12/3/2020
                <span className={cn(Styles.green, "float-right")}>+29</span>
              </div>
              <div>
                11/28/2020
                <span className={cn(Styles.green, "float-right")}>+50</span>
              </div>
              <div>
                11/14/2020
                <span className={cn(Styles.red, "float-right")}>-120</span>
              </div>
              <div>
                10/10/2020
                <span className={cn(Styles.green, "float-right")}>+26</span>
              </div>
            </div>
          </div>
        }
      />
    </div>
  );
};

export default AccountInfo;
