import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import {Wallet} from "@modules/account/pages/Wallet";

function WalletPage(props: Record<any, any>) {
    return (
        <PageTwoColumns>
            <Wallet/>
        </PageTwoColumns>
    );
}

export default WalletPage;