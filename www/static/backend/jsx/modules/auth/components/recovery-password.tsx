import React from "react";
import { Form } from "react-bootstrap";
import appData from "@admin/utils/app-data";

const RecoveryPassword: React.FC<any> = function (props: any) {
  function errorMessageTemplate(field) {
    const error = appData("resetPasswordForm.errors." + field);

    if (error) {
      return (
        <div className="admin-form-control mb-2.25 text-center form-error-message">
          {error}
        </div>
      );
    }
  }

  function passwordSentTemplate() {
    const email = appData("resetPasswordForm.sentTo");

    return (
      <React.Fragment>
        <p>
          Email with your account username and password was sent to{" "}
          <b>{email}</b>. <br /> Use this data to login to the shop.
        </p>
      </React.Fragment>
    );
  }

  function contentTemplate() {
    // если письмо уже было отправлено
    if (appData("resetPasswordForm.sentTo")) {
      return passwordSentTemplate();
    }

    return (
      <React.Fragment>
        <p>
          You can recover your lost account information using the form below.
          Please enter your valid email address (the one you used for
          registration), your account information will be mailed to you shortly.
        </p>

        <div className="d-flex justify-content-center">
          <form
            method="post"
            name="errorform"
            className="login-form d-flex flex-column align-items-end"
          >
            <input type="hidden" name="is_remember" value="Y" />
            <input type="hidden" name="usertype" value="A" />
            <input type="hidden" name="redirect" value="admin" />
            <input type="hidden" name="mode" value="login" />

            <Form.Group
              className="mb-3 d-flex align-items-center"
              controlId="recoveryPasswordEmail"
            >
              <Form.Label className="mr-3 form-label">email</Form.Label>

              <Form.Control
                className="form-input admin-form-control"
                type="text"
                name="ResetPasswordForm[email]"
                required
              />
            </Form.Group>

            {errorMessageTemplate("email")}

            <button type="submit" className="admin-form-control form-button">
              Submit
            </button>

            <div className="admin-form-control mt-2.25 text-center">
              <a href="/admin/login" className="recover-password-link">
                Back to login
              </a>
            </div>
          </form>
        </div>
      </React.Fragment>
    );
  }

  return <div>{contentTemplate()}</div>;
};

export default RecoveryPassword;
