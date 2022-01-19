import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import TSVAddNewApp from "@modules/account/components/login-and-security/TSVAddNewApp";
import { getInstance } from "@services/axios/Instance";
import { AxiosResponse } from "axios";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  const user: Record<any, any> = process.initialState.user;
  let tsv;

  if (user) {
    await instance
      .get("/api-client/user/tsv/get")
      .then((res: AxiosResponse) => {
        tsv = res.data;
      });
  }

  return {
    props: { tsv },
  };
}

const TSVAddNewAppPage: React.FC = function (props: Record<any, any>) {
  return (
    <PageTwoColumns>
      <TSVAddNewApp tsv={props.tsv} />
    </PageTwoColumns>
  );
};

export default TSVAddNewAppPage;
