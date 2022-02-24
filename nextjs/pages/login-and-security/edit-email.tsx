import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import FormEditUserEmail from "@modules/account/components/login-and-security/FormEditUserEmail";

const EditEmail: React.FC = function () {
  return (
    <PageTwoColumns>
      <FormEditUserEmail />
    </PageTwoColumns>
  );
};

export default EditEmail;
