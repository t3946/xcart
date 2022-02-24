import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import PublicProfile from "@modules/account/components/public-profile/PublicProfile";

function PublicProfilePage() {
  return (
    <PageTwoColumns>
      <PublicProfile />
    </PageTwoColumns>
  );
}

export default PublicProfilePage;
