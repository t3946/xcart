import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import FormEditUserName from "@modules/account/components/login-and-security/FormEditUserName";

const EditName: React.FC = function () {
  return (
    <PageTwoColumns>
      <FormEditUserName />
    </PageTwoColumns>
  );
};

export default EditName;
