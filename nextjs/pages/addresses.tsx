import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import { Addresses } from "@modules/account/pages/Addresses";

function AddressesPage() {
  return (
    <PageTwoColumns>
      <Addresses />
    </PageTwoColumns>
  );
}

export default AddressesPage;
