import { GetServerSidePropsContext, NextPage } from "next";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { getInstance } from "@services/axios/Instance";
import { InvitationPage } from "@modules/account/pages/InvitationPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import React from "react";

interface IProps {
  role: any;
  list: any;
  iv: any;
  content: any;
}

export async function getServerSideProps(ctx: GetServerSidePropsContext) {
  const { iv, content } = ctx.query;
  const instance = getInstance(ctx.req);
  const props: Record<any, any> = { iv, content };

  await instance
    .get(`/api-client/user/lists/invite/info/${iv}/${content}`)
    .then((res) => {
      props.list = res.data.list;
      props.role = res.data.role;
    })
    .catch(() => {
      props.list = null;
      props.role = null;
    });

  return {
    props,
  };
}

const InviteList: NextPage<IProps> = (props) => {
  const user = useSelectorAccount((e) => e.user);
  const router = useRouter();

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  if (!user) {
    return null;
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      <InvitationPage
        role={props.role}
        list={props.list}
        iv={props.iv}
        content={props.content}
      />
    </PageTwoColumns>
  );
};

export default InviteList;
