import React from "react";
import { Form } from "react-bootstrap";
import appData from "@admin/utils/app-data";

const Login: React.FC<any> = function (props: any) {
  function errorMessageTemplate(fieldName) {
    const error = appData(`loginForm.errors.${fieldName}`);

    if (error) {
      return (
        <div className="admin-form-control mb-2.25 text-center form-error-message">
          {error}
        </div>
      );
    }
  }

  return (
    <div>
      <p className="text-message mb-4">
        If you already have an account, you can authenticate yourself by filling
        in the form below.
      </p>

      <div className="d-flex justify-content-center">
        <form
          action="/admin/login"
          method="post"
          className="form d-flex flex-column align-items-end"
        >
          <input type="hidden" name="is_remember" value="Y" />
          <input type="hidden" name="usertype" value="A" />
          <input type="hidden" name="redirect" value="admin" />
          <input type="hidden" name="mode" value="login" />

          <Form.Group
            className="mb-3 d-flex align-items-center"
            controlId="loginEmail"
          >
            <Form.Label className="mr-3 form-label">username</Form.Label>

            <Form.Control
              className="form-input admin-form-control"
              type="text"
              name="LoginForm[login]"
              required
            />
          </Form.Group>

          {errorMessageTemplate("login")}

          <Form.Group
            className="mb-3 d-flex align-items-center"
            controlId="loginPassword"
          >
            <Form.Label className="mr-3 form-label">password</Form.Label>

            <Form.Control
              className="form-input admin-form-control"
              type="password"
              name="LoginForm[password]"
              required
            />
          </Form.Group>

          {errorMessageTemplate("password")}

          <button type="submit" className="admin-form-control form-button">
            Login
          </button>

          <div className="admin-form-control mt-2.25 text-center">
            <a
              href="/admin/recovery-password"
              className="recover-password-link"
            >
              Recover password
            </a>
          </div>
        </form>
      </div>
    </div>
  );
};

export default Login;
