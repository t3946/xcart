import React from "react";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuestionCircle } from "@fortawesome/free-solid-svg-icons/faQuestionCircle";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import { loginAction } from "@redux/actions/account-actions/AutorizationActions";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useRouter } from "next/router";
import Link from "next/link";
import cn from "classnames";
import Styles from "@modules/account/components/authorization/LoginForm.module.scss";
import generateFp from "@utils/generateFp";
import Tooltip from "@components/common/tooltip/Tooltip";
import { formatPhone } from "@utils/phoneNumber";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { EMethodType } from "@modules/account/components/login-and-security/tsv/Methods";

interface IProps {
  login: string;
  goToInputLogin: (resetLogin?: boolean) => void;
  goToOTPInput: () => void;
  setPassword: (password: string) => void;
  setRememberMe: (rememberMe: boolean) => void;
  isCaptchaRequired: boolean;
  setIsCaptchaRequired: (isCaptchaRequired: boolean) => void;
  setTsvMethod: (method: EMethodType) => void;
}

const LoginFormInputPassword = function (props: IProps): any {
  const dispatch = useDispatch();
  const router = useRouter();
  const inputRef = React.createRef<HTMLInputElement>();
  const {
    goToInputLogin,
    goToOTPInput,
    login,
    setPassword,
    setRememberMe,
    isCaptchaRequired,
    setIsCaptchaRequired,
    setTsvMethod,
  } = props;
  const [captcha, setCaptcha] = React.useState<number>(null);
  const google_recaptchav2_site_key = useSelectorAccount(
    (e) => e.config.google_recaptchav2_site_key
  );

  React.useEffect(() => {
    inputRef.current?.focus();
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    if (grecaptcha && captcha === null && isCaptchaRequired) {
      const widget = grecaptcha.render(
        document.getElementById("g-recaptcha-v2"),
        {
          sitekey: google_recaptchav2_site_key,
        }
      );

      setCaptcha(widget);
    }
  }, []);

  const initialState = {
    password: "",
    rememberMe: false,
  };
  const validationSchema = yup.object().shape({
    password: yup
      .string()
      .required("Password is a required field")
      .min(8, "Password must be at least 8 characters")
      .max(32, "Password must be at most 32 characters"),
    rememberMe: yup.bool(),
  });

  function submit(values: any, actions: any) {
    //function submit must be synchronous because need wrap async part
    (async function wrapAsyncFunc() {
      actions.setSubmitting(true);

      const data = {
        login,
        password: values.password,
        rememberMe: values.rememberMe,
        fingerprint: await generateFp(),
      };

      if (captcha !== null) {
        data.captcha = grecaptcha.getResponse(captcha);
      }

      dispatch(
        loginAction({
          form: data,

          success(res: any) {
            actions.setSubmitting(false);

            if (
              !isCaptchaRequired &&
              res.data.error &&
              res.data.error.password === "Captcha required"
            ) {
              setIsCaptchaRequired(true);

              if (captcha === null) {
                const widget = grecaptcha.render(
                  document.getElementById("g-recaptcha-v2"),
                  {
                    sitekey: google_recaptchav2_site_key,
                  }
                );

                setCaptcha(widget);
              }
              return;
            }

            if (res.data.user) {
              setIsCaptchaRequired(false);
              dispatch(userSetAction(res.data.user));
              goToInputLogin(true);
              router.push("/dashboard");
              return;
            }

            if (captcha !== null) {
              grecaptcha.reset(captcha);
            }

            if (res.data.error.message === "tsv required") {
              setPassword(data.password);
              setRememberMe(data.rememberMe);
              setTsvMethod(res.data.error.method);
              goToOTPInput();
              return;
            }

            actions.setErrors({ password: res.data.error.password });
          },
        })
      );
    })();
  }

  return (
    <>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {(formikProps) => {
          const { isSubmitting, handleChange, values, errors, touched } =
            formikProps;

          return (
            <Form>
              <div className="px-12 px-sm-0 mt-sm-4 mt-lg-0">
                <p
                  className={cn(
                    Styles.authFormInfo,
                    "auth-form-info",
                    "d-flex",
                    "justify-content-between",
                    "mt-3",
                    "mb-3",
                    "mb-sm-14",
                    "mb-lg-14"
                  )}
                >
                  {login && (
                    <span>
                      {login[0] === "+" ? formatPhone(login, true) : login}
                    </span>
                  )}

                  <a
                    href="#"
                    onClick={() => goToInputLogin()}
                    className={Styles.commonLink}
                  >
                    Change
                  </a>
                </p>

                <RBForm.Group controlId="LoginFormPassword">
                  <Label className="d-flex mb-10 mb-sm-2 mb-lg-2 justify-content-between align-items-center">
                    <span className={"form-input-label mb-0"}>Password</span>

                    <Link href={"/password-assistance"}>
                      <a className={cn(Styles.authFormInfo, Styles.commonLink)}>
                        Forgot your password?
                      </a>
                    </Link>
                  </Label>

                  <Input
                    ref={inputRef}
                    type="password"
                    name="password"
                    value={values.password}
                    onChange={handleChange}
                    isInvalid={!!errors.password && !!touched.password}
                  />
                  <Feedback type="invalid">
                    {!!touched.password && errors.password}
                  </Feedback>

                  <div
                    id="g-recaptcha-v2"
                    className={cn([
                      "g-recaptcha",
                      {
                        "mt-3": captcha !== null,
                      },
                    ])}
                    data-sitekey={google_recaptchav2_site_key}
                  />
                </RBForm.Group>
              </div>

              <button
                type="submit"
                className={cn(
                  "form-button login-form_submit-button",
                  Styles.button,
                  "mb-16",
                  "mb-sm-4"
                )}
                disabled={isSubmitting}
              >
                sign-in
              </button>

              <RBForm.Group className={"mb-0 px-12 px-sm-0"}>
                <input
                  name="rememberMe"
                  onChange={handleChange}
                  id="rememberMe"
                  className="form-checkbox d-none"
                  type="checkbox"
                  checked={values.rememberMe}
                />

                <RBForm.Label
                  className={
                    "checkbox-label mb-0 align-items-center d-flex form-label"
                  }
                  htmlFor={"rememberMe"}
                >
                  <div className={cn(Styles.authFormInfo, "auth-form-info")}>
                    Keep me signed in.{" "}
                    <Tooltip
                      overlay={
                        <div>
                          <h2 className="common-tooltip-header">
                            "Keep Me Signed In" Checkbox
                          </h2>

                          <p className={"text-align--left auth-form-info"}>
                            Choosing "Keep me signed in" reduces the number of
                            times you're asked to Sign-In on this device.
                          </p>

                          <p className={"text-align--left auth-form-info mb-0"}>
                            To keep your account secure, use this option only on
                            your personal devices.
                          </p>
                        </div>
                      }
                    >
                      <span className={"common-link"}>
                        Details
                        <FontAwesomeIcon
                          className={"ms-1"}
                          icon={faQuestionCircle}
                        />
                      </span>
                    </Tooltip>
                  </div>
                </RBForm.Label>
              </RBForm.Group>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default LoginFormInputPassword;
