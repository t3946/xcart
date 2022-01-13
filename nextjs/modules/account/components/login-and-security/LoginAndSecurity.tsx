import React from "react";
import { useRouter } from "next/router";
import { useDispatch } from "react-redux";
import classnames from "classnames";
import { getCountryByCode } from "@utils/Countries";
import Alert from "@modules/account/components/shared/Alert";
import { setAlertAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import GreyGrid from "@modules/ui/GreyGrid";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import cn from "classnames";
import Styles from "@modules/account/components/login-and-security/LoginAndSecurity.module.scss";

const LoginAndSecurity = (): any => {
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);
  const countries = useSelectorAccount((e) => e.countries);
  const alert = useSelectorAccount((e) => e.loginAndSecurity.alert);
  const [show, setShow] = React.useState(alert !== null);
  const ALERT_SHOW_TIME_MS = 3000;

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  }, []);

  const listItems = [
    {
      title: "full name",
      caption: user.name,
      route: "/login-and-security/edit-name",
    },
    {
      title: "email",
      caption: user.email,
      route: "/login-and-security/edit-email",
    },
    {
      title: "mobile phone number",
      caption: formatPhoneNumber(),
      route: "/login-and-security/edit-phone",
    },
    {
      title: "password",
      caption: "********",
      route: "/login-and-security/edit-password",
    },
    {
      title: "two-step verification (2SV) settings",
      caption: "Manage your Two-Step Verification (2SV) Authenticators",
      classes: {
        caption: Styles.gridItemCaption_small,
      },
      route: "/login-and-security/two-step-verification-settings",
    },
    {
      title: "secure your account",
      caption:
        "If you think your S3 Stores account has been compromised, follow these steps to make your account more secure",
      classes: {
        container: Styles.gridItemContainer_small,
        caption: Styles.gridItemCaption_small,
      },
      route: "",
    },
  ];

  if (alert) {
    breakpoint({
      xs: function () {
        dispatch(setAlertAction(null));
        dispatch(setMobileAlertAction(alert));
        dispatch(showMobileAlertAction(true));
        dispatch(setVisibleShadowPanelAction(true));
      },
      md: function () {
        setTimeout(() => {
          setShow(false);
          setTimeout(() => {
            dispatch(setAlertAction(null));
          }, 500);
        }, ALERT_SHOW_TIME_MS);
      },
    });
  }

  function formatPhoneNumber() {
    if (!user.phone || !user.phone_country_code) {
      return "N/A";
    }

    const phoneCountry = getCountryByCode(user.phone_country_code, countries);

    const countryPrefix = "+" + phoneCountry.phone_code;
    return user.phone.replace(countryPrefix, `${countryPrefix} `);
  }

  function settingsItemsTemplate() {
    const items = [];

    for (const i in listItems) {
      const listItem = listItems[i];

      items.push(
        <div className="login-and-security-settings_item" key={i}>
          <div
            className={cn(
              Styles.gridItemContainer,
              listItem.classes?.container
            )}
          >
            <div className={"login-and-security-settings-item-text"}>
              <b className={Styles.gridItemTitle}>{listItem.title}:</b>
              <span
                className={classnames(
                  "d-block",
                  "settings-item-caption",
                  listItem.classes?.caption
                )}
              >
                {listItem.caption}
              </span>
            </div>

            <button
              onClick={() => router.push(listItem.route)}
              className={cn(
                "form-button",
                "form-button__outline",
                "login-and-security-edit-button",
                "d-block",
                "d-md-inline-block",
                "mt-12",
                "mt-md-0",
                Styles.button_edit
              )}
            >
              edit
            </button>
          </div>
        </div>
      );
    }

    return items;
  }

  React.useEffect(() => {
    return () => {
      dispatch(setAlertAction(null));
    };
  });

  function beforePage(): any {
    return (
      <Alert
        show={show}
        variant={alert?.variant}
        message={alert?.message}
        classes={{
          container: "d-none d-md-block pt-20 pb-5 pt-lg-0",
          alert: "account-inner-page_alert",
        }}
      />
    );
  }

  return (
    <InnerPage
      beforePage={beforePage()}
      hatClasses={Styles.pageHat}
      header={"Login & security"}
      bodyClasses={["p-0", Styles.pageBody]}
      footerClasses={["d-md-flex", "justify-content-md-center", "d-lg-block"]}
      footer={
        <button
          className={cn(
            "form-button",
            "w-md-auto",
            "d-inline-block",
            Styles.button
          )}
          onClick={() => router.push("/dashboard")}
        >
          done
        </button>
      }
    >
      <GreyGrid
        classes={{ item: [Styles.gridItem, Styles.grid__item] }}
        items={settingsItemsTemplate()}
      />
    </InnerPage>
  );
};

export default LoginAndSecurity;
