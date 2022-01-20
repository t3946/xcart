import React from "react";
import { Formik, Form, FormikHelpers } from "formik";
import { Form as RBForm } from "react-bootstrap";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";
import * as yup from "yup";
import { AxiosResponse } from "axios";
import { userSetAction } from "@redux/actions/account-actions/UserActions";
import { useDispatch } from "react-redux";
import generateFp from "@utils/generateFp";
import { loginAction } from "@redux/actions/account-actions/AutorizationActions";
import cn from "classnames";

import StylesLoginForm from "@modules/account/components/authorization/LoginForm.module.scss";
import Styles from "@modules/account/components/authorization/LoginFormInputOTP.module.scss";

interface IProps {
  login: string;
  password: string;
  rememberMe: boolean;
}

const LoginFormInputOTP: React.FC<IProps> = function (props: IProps): any {
  const { login, password, rememberMe } = props;
  const inputRef = React.createRef<HTMLInputElement>();
  const dispatch = useDispatch();
  const initialState = {
    code: "",
    rememberBrowser: false,
  };
  const validationSchema = yup.object().shape({
    code: yup.string().required("OTP is a required field"),
    rememberBrowser: yup.bool(),
  });

  function submit(values: Record<any, any>, actions: FormikHelpers<any>) {
    const data: Record<any, any> = { ...values, password, login, rememberMe };

    actions.setSubmitting(true);

    generateFp().then((fp) => {
      if (data.rememberBrowser) {
        data.fingerprint = fp;
      }

      dispatch(
        loginAction({
          form: data,

          success(res: AxiosResponse) {
            if (res.data.error) {
              actions.setErrors(res.data.error);
              actions.setSubmitting(false);
              return;
            }

            if (res.data.user) {
              dispatch(userSetAction(res.data.user));
              return;
            }
          },
        })
      );
    });
  }

  return (
    <Formik
      initialValues={initialState}
      validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ isSubmitting, handleChange, values, errors, touched }) => {
        return (
          <Form>
            <div className="px-12 px-sm-0">
              <p
                className={cn(
                  Styles.caption,
                  "auth-form-info",
                  "mb-18",
                  "mb-sm-12",
                  "mb-lg-14"
                )}
              >
                For added security, please enter the One Time Password (OTP)
                generation by your by Authenticator App
              </p>

              <RBForm.Group controlId="LoginFormPassword">
                <Label className="d-block mb-12 mb-sm-2 mb-lg-1">
                  <span className={"form-input-label"}>Enter OTP</span>
                </Label>

                <Input
                  ref={inputRef}
                  type="text"
                  name="code"
                  value={values.code}
                  onChange={handleChange}
                  className={"form-input"}
                  isInvalid={touched.code && !!errors.code}
                  disabled={isSubmitting}
                />

                <Feedback type="invalid">
                  {!!touched.code && errors.code}
                </Feedback>
              </RBForm.Group>

              <RBForm.Group className={"mb-0 mt-3"}>
                <input
                  name="rememberBrowser"
                  onChange={handleChange}
                  id="rememberBrowser"
                  className="form-checkbox"
                  type="checkbox"
                  checked={values.rememberBrowser}
                  disabled={isSubmitting}
                />

                <RBForm.Label
                  className={
                    "checkbox-label mb-0 align-items-center d-flex form-label"
                  }
                  htmlFor={"rememberBrowser"}
                >
                  <div className={cn(StylesLoginForm.text, "auth-form-info")}>
                    Don’t require OTP on this browser
                  </div>
                </RBForm.Label>
              </RBForm.Group>
            </div>

            <button
              type="submit"
              className={cn(
                "form-button",
                "mt-4",
                "mb-3",
                "mb-lg-12",
                StylesLoginForm.button
              )}
              disabled={isSubmitting}
            >
              sign-in
            </button>

            <div className="px-12 px-sm-0">
              <a href={"/contactus/"} className={"auth-form-info"}>
                Two-step Verification account recovery
              </a>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default LoginFormInputOTP;
