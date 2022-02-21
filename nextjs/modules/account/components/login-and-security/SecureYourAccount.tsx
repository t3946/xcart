import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import AlertExclamationTriangle from "@modules/icon/components/account/exclamation-triangle/AlertExclamationTriangle";
import Button, { ETheme } from "@modules/ui/forms/Button";
import cn from "classnames";
import { useRouter } from "next/router";

import Styles from "@modules/account/components/login-and-security/SecureYourAccount.module.scss";

function SecureYourAccount() {
  const router = useRouter();

  function stepItem(
    number: number,
    title: string,
    caption: string | React.ReactNode,
    tip: string,
    button?: React.ReactNode
  ) {
    return (
      <>
        <h3 className={cn(Styles.stepTitle, "fw-bold")}>
          Step {number}
          <div>{title}</div>
        </h3>
        <div
          className={cn(
            Styles.stepBody,
            "d-flex justify-content-between align-items-lg-center"
          )}
        >
          <div>
            <div className={cn("mb-2", Styles.stepCaption)}>{caption}</div>
            <div className={Styles.stepTip}>Tip: {tip}</div>
          </div>
          <div>{button}</div>
        </div>
      </>
    );
  }

  return (
    <InnerPage
      hatClasses={"pb-20"}
      header={"Secure Your Account"}
      footer={
        <Button
          type={"button"}
          onClick={() => router.push("/login-and-security")}
          className={"w-md-auto mx-md-auto mx-lg-0 px-5"}
        >
          Done
        </Button>
      }
    >
      <p className={cn(Styles.pageCaption, "px-12")}>
        To protect your S3 Stores account, we recommend taking the following
        steps immediately.
      </p>

      <GreyGrid
        classes={{
          list: [Styles.pageTable, Styles.page__table],
          item: [Styles.table_item, Styles.tableItem],
        }}
        items={[
          stepItem(
            1,
            "Update your email settings",
            `Use a strong, unique password for your account not used anywhere else. Check for "email forwarding" rules, and remove any found.`,
            "If your email account password was hacked, your S3 Stores account might be at risk. If your email was forwarded to another address, your account might be at risk"
          ),
          stepItem(
            2,
            "Set mobile PIN/passcode",
            `Contact your mobile phone provider and add a PIN/Passcode to protect your mobile phone account.`,
            "If your mobile account or SMS is hacked, your S3 Stores account might be at risk"
          ),
          stepItem(
            3,
            "Sign out all apps, devices, and web browsers",
            <div className="d-flex align-items-center">
              <AlertExclamationTriangle
                className={"alert-icon__warning me-2"}
              />
              50 app(s) signed in to your S3 Stores account
            </div>,
            "For maximum security, sign out of everything",
            <Button
              type={"button"}
              theme={ETheme.themeDarkGrey}
              className={"w-md-auto"}
            >
              Sign-out everything
            </Button>
          ),
        ]}
      />
    </InnerPage>
  );
}

export default SecureYourAccount;
