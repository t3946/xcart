import React from "react";
import { useRouter } from "next/router";
import { route } from "@utils/AppData";
import { useDispatch, useSelector } from "react-redux";
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

const LoginAndSecurity = (): any => {
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);
  const countries = useSelectorAccount((e) => e.countries);
  const alert = useSelectorAccount((e) => e.loginAndSecurity.alert);
  const [show, setShow] = React.useState(alert !== null);
  const ALERT_SHOW_TIME_MS = 3000;

  if (!user) {
    router.push("/login");
    return;
  }

  const listItems = [
    {
      title: "full name",
      caption: user.name,
      route: route("account:edit-name"),
    },
    {
      title: "email",
      caption: user.email,
      route: route("account:edit-email"),
    },
    {
      title: "mobile phone number",
      caption: formatPhoneNumber(),
      route: route("account:edit-phone"),
    },
    {
      title: "password",
      caption: "********",
      route: route("account:edit-password"),
    },
    {
      title: "two-step verification (2SV) settings",
      caption: "Manage your Two-Step Verification (2SV) Authenticators",
      classes: {
        caption: "settings-item-caption__small",
      },
      route: route("account:two-step-verification-settings"),
    },
    {
      title: "secure your account",
      caption:
        "If you think your S3 Stores account has been compromised, follow these steps to make your account more secure",
      classes: {
        caption: "settings-item-caption__small",
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
    const phoneCountry = getCountryByCode(user.phone_country_code, countries);

    if (!phoneCountry) {
      return "N/A";
    }

    const countryPrefix = "+" + phoneCountry.phone_code;
    return user.phone.replace(countryPrefix, `${countryPrefix} `);
  }

  function settingsItemsTemplate() {
    const items = [];

    for (const listItem of listItems) {
      items.push(
        <div className="login-and-security-settings_item">
          <div className="login-and-security-settings-item-container">
            <div className={"login-and-security-settings-item-text"}>
              <b className="settings-item-title">{listItem.title}:</b>
              <br />
              <span
                className={classnames(
                  "settings-item-caption",
                  listItem.classes?.caption
                )}
              >
                {listItem.caption}
              </span>
            </div>

            <button
              onClick={() => router.push(listItem.route)}
              className="form-button form-button__outline login-and-security-edit-button d-block d-md-inline-block mt-12 mt-md-0"
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
    return breakpoint({
      md: function () {
        return (
          <Alert
            show={show}
            variant={alert?.variant}
            message={alert?.message}
            classes={{
              container: "pt-20 pb-5 pt-lg-0",
              alert: "account-inner-page_alert",
            }}
          />
        );
      },
    });
  }

  return (
    <>
      <InnerPage
        beforePage={beforePage()}
        header={"Login & security"}
        bodyClasses={"p-0"}
        footer={
          <button
            className={
              "admin-form-control form-button w-md-auto d-inline-block"
            }
            onClick={() => router.push("/dashboard")}
          >
            done
          </button>
        }
      >
        <GreyGrid items={settingsItemsTemplate()} />
      </InnerPage>
    </>
  );
};

export default LoginAndSecurity;
