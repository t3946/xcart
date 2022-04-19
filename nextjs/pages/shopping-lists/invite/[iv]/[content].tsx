import { GetServerSidePropsContext, NextPage } from "next";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { getInstance } from "@services/axios/Instance";
import { InvitationPage } from "@modules/account/pages/InvitationPage";

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
    });

  return {
    props,
  };
}

const InviteList: NextPage<IProps> = (props) => {
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
