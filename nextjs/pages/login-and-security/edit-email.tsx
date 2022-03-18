import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import EditEmail from "@components/pages/login-and-security/edit-email/EditEmail";

const EditEmailPage: React.FC = function () {
  return (
    <PageTwoColumns>
      <EditEmail />
    </PageTwoColumns>
  );
};

export default EditEmailPage;
