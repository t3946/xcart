import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import {Transactions} from "@modules/account/pages/Transactions";

function TransactionsPage(props: Record<any, any>) {
    return (
        <PageTwoColumns>
            <Transactions/>
        </PageTwoColumns>
    );
}

export default TransactionsPage;