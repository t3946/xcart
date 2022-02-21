import React from "react";
import InnerPage from "@components/common/inner-page/InnerPage";
import GreyGrid from "@components/common/grey-grid/GreyGrid";
import AlertExclamationTriangle from "@modules/icon/components/account/exclamation-triangle/AlertExclamationTriangle";
import Button, { ETheme } from "@modules/ui/forms/Button";
import cn from "classnames";
import { useRouter } from "next/router";
import { Formik, Form, FormikHelpers } from "formik";
import Styles from "@modules/account/components/login-and-security/SecureYourAccount.module.scss";
import { requireForAllAction } from "@redux/actions/account-actions/TSVActions";
import { AxiosResponse } from "axios";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function SecureYourAccount() {
  const router = useRouter();
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);

  function submit(values: any, actions: FormikHelpers<any>) {
    actions.setSubmitting(true);

    dispatch(
      requireForAllAction({
        success(res: AxiosResponse) {
          dispatch(userSetAction(res.data.user));
          actions.setSubmitting(false);
        },
      })
    );
  }

  function stepItem(
    number: number,
    title: string,
    caption: string | React.ReactNode,
    tip: string | null,
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
            {tip && <div className={Styles.stepTip}>Tip: {tip}</div>}
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
              {user.tsv_suppressed > 0 && (
                <AlertExclamationTriangle
                  className={"alert-icon__warning me-2"}
                />
              )}
              {user.tsv_suppressed} app(s) signed in to your S3 Stores account
            </div>,
            user.tsv_suppressed
              ? "For maximum security, sign out of everything"
              : null,
            <Formik initialValues={{}} onSubmit={submit}>
              {function ({ isSubmitting }) {
                return (
                  <Form>
                    <Button
                      type={"submit"}
                      theme={ETheme.themeDarkGrey}
                      className={"w-md-auto"}
                      disabled={isSubmitting || user.tsv_suppressed === 0}
                    >
                      Sign-out everything
                    </Button>
                  </Form>
                );
              }}
            </Formik>
          ),
        ]}
      />
    </InnerPage>
  );
}

export default SecureYourAccount;
