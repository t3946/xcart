import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import FormEditUserPhone from "@modules/account/components/login-and-security/FormEditUserPhone";

const EditPhone: React.FC = function () {
  return (
    <PageTwoColumns>
      <FormEditUserPhone />
    </PageTwoColumns>
  );
};

export default EditPhone;
