import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import EditPhone from "@components/pages/login-and-security/edit-phone/EditPhone";

const EditPhonePage: React.FC = function () {
  return (
    <PageTwoColumns>
      <EditPhone />
    </PageTwoColumns>
  );
};

export default EditPhonePage;
