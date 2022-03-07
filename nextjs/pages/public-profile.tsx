import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import PublicProfile from "@modules/account/components/public-profile/PublicProfile";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function PublicProfilePage() {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  return (
    <PageTwoColumns>
      <PublicProfile />
    </PageTwoColumns>
  );
}

export default PublicProfilePage;
