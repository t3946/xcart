import { GetServerSidePropsContext, NextPage } from "next";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import ListsSidebarMenu from "@modules/account/components/lists/ListsSidebarMenu";
import { getInstance } from "@services/axios/Instance";
import { InvitationPage } from "@modules/account/pages/InvitationPage";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import React from "react";
import { loadLists } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import Link from "next/link";

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
  const { role, list: listNew, iv, content } = props;
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();
  const router = useRouter();
  const { lists } = useSelectorAccount((state) => state.lists);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  if (!user) {
    return null;
  }

  if (lists === null) {
    dispatch(loadLists());
    return null;
  }

  let isAlreadyHasList = false;

  for (const list of lists) {
    if (listNew.product_list_id === list.product_list_id) {
      isAlreadyHasList = true;
      break;
    }
  }

  return (
    <PageTwoColumns bar={<ListsSidebarMenu />}>
      {isAlreadyHasList ? (
        <p>
          You already added in this{" "}
          <Link href={`/shopping-lists/${list.product_list_id}`}>
            <a>list</a>
          </Link>
        </p>
      ) : (
        <InvitationPage role={role} list={listNew} iv={iv} content={content} />
      )}
    </PageTwoColumns>
  );
};

export default InviteList;
