import React from "react";
import { useHistory } from "react-router-dom";
import { route } from "@utils/AppData";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import classnames from "classnames";
import { getCountryByCode } from "@utils/Countries";
import Alert from "@modules/account/components/shared/Alert";
import StoreInterface from "@modules/account/ts/types/store.type";
import { setAlertAction } from "@redux/actions/account-actions/LoginAndSecurityActions";
import InnerPage from "@modules/account/components/shared/InnerPage";
import {
  setIsVisibleAction as showMobileAlertAction,
  setMobileAlertAction,
} from "@redux/actions/account-actions/MobileMenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

const LoginAndSecurity = (): any => {
  const dispatch = useDispatch();
  const breakpoint = useBreakpoint();
  const history = useHistory();
  const user = useSelector((e: StoreDto) => e.user);
  const countries = useSelector((e: StoreInterface) => e.countries);
  const alert = useSelector((e: StoreInterface) => e.loginAndSecurity.alert);
  const [show, setShow] = React.useState(alert !== null);
  const ALERT_SHOW_TIME_MS = 3000;

  if (!user) {
    history.push(route("account:login"));
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
        <li className="login-and-security-settings-item login-and-security-settings_item">
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
              onClick={() => history.push(listItem.route)}
              className="form-button form-button__outline login-and-security-edit-button d-block d-md-inline-block mt-12 mt-md-0"
            >
              edit
            </button>
          </div>
        </li>
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
        bodyClasses={"content-panel login-and-security-content-panel p-0"}
        footer={
          <button
            className={
              "admin-form-control form-button w-md-auto d-inline-block"
            }
            onClick={() => history.push(route("account:dashboard"))}
          >
            done
          </button>
        }
      >
        <ul className={"list-unstyled m-0"}>{settingsItemsTemplate()}</ul>
      </InnerPage>
    </>
  );
};

export default LoginAndSecurity;
