import { GetServerSidePropsContext, NextPage } from "next";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { getInstance } from "@services/axios/Instance";
import { InvitationPage } from "@modules/account/pages/InvitationPage";
interface InviteList {
  data: any;
}
const InviteList: NextPage<InviteList> = ({ data }) => {
  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      <InvitationPage {...data} />
    </PageTwoColumns>
  );
};
export default InviteList;
export const getServerSideProps = async (
  context: GetServerSidePropsContext
) => {
  const { tag, code } = context.query;
  const instance = getInstance(context.req);
  return await instance
    .get(`/api/account/lists/check-invite/${tag}/${code}`)
    .then((res) => {
      switch (res.status) {
        case 200:
          return { props: { data: res.data } };
        default:
          return {
            redirect: {
              permanent: false,
              destination: "/",
            },
          };
      }
    })
    .catch((e) => console.log(e));
};
