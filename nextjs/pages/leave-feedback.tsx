import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import LeaveFeedback from "@modules/account/components/leave-feedback/LeaveFeedback";

const LeaveFeedbackPage: React.FC<any> = function () {
  return (
    <PageTwoColumns>
      <LeaveFeedback />
    </PageTwoColumns>
  );
};

export default LeaveFeedbackPage;
