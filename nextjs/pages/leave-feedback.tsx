import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import LeaveFeedback from "@modules/account/components/leave-feedback/LeaveFeedback";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const LeaveFeedbackPage: React.FC<any> = function () {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  return (
    <PageTwoColumns>
      <LeaveFeedback />
    </PageTwoColumns>
  );
};

export default LeaveFeedbackPage;
