import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import FormChangePassword from "@modules/account/components/login-and-security/FormChangePassword";

const EditPassword: React.FC = function () {
  return (
    <PageTwoColumns>
      <FormChangePassword />
    </PageTwoColumns>
  );
};

export default EditPassword;
