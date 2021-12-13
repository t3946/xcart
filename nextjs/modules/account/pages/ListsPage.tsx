import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { ListHeader } from "@modules/account/components/lists/ListHeader";
import { Button } from "@material-ui/core";
import { ListProductItems } from "@modules/account/components/lists/ListProductItems";
import { useRouter } from "next/router";
import StoreInterface from "@modules/account/ts/types/store.type";
import { Sceleton } from "@modules/shared/components/sceleton/Sceleton";
import { ListProductItemSkeleton } from "../components/lists/ListProductItemSkeleton";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { AddIdea } from "@modules/account/components/lists/AddIdea";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { ListMobileMenu } from "@modules/account/components/lists/ListMobileMenu";
import { List } from "@modules/account/ts/types/list.type";
import { MobileMenuBackBtn } from "@modules/account/pages/MobileMenuBackBtn";

const ListsPage: React.FC = () => {
  //todo: cant get id param form url
  const { id }: { id: string } = { id: "0" };

  const lists = useSelector((e: StoreInterface) => e.lists.lists);

  const [list, setList] = useState<List | null>(null);

  const createIdeaDialog = useDialog();

  const breakpoints = useBreakpoint();

  const router = useRouter();

  const edit = list?.list_info.role !== UserPrivateVariantsEnum.VIEW;

  //todo: router get
  const viewLists = () => {
    return (
      <React.Fragment>
        <ListHeader
          shippingList={!!id}
          label={list?.name}
          edit={edit}
          info={list}
        />
        <ListProductItems edit={edit} path={router.pathname} info={list} />
      </React.Fragment>
    );
  };

  useEffect(() => {
    if (lists && id) {
      setList(lists.find((e) => e.cache_url === id));
    } else if (lists && !id) {
      setList(lists[0]);
    }
  }, [lists]);

  return (
    <div>
      {id && (
        <MobileMenuBackBtn
          redirectUrl={`/account/your-lists/`}
          label={"account"}
        />
      )}
      {!!list ? (
        breakpoints({
          xs: id ? viewLists() : <ListMobileMenu lists={lists} />,
          lg: viewLists(),
        })
      ) : (
        <React.Fragment>
          <div className="list-header-container">
            <Sceleton height={36} maxWidth={"100%"} />
          </div>

          {Array.from({ length: 3 }, (v, k) => k).map((value, index) => {
            return <ListProductItemSkeleton key={index} />;
          })}
        </React.Fragment>
      )}
      <BootstrapDialogHOC
        show={createIdeaDialog.open}
        title={"Create a new idea"}
        onClose={createIdeaDialog.handleClose}
      >
        <AddIdea
          listHash={id || list?.cache_url}
          onCancelBtnClick={createIdeaDialog.handleClose}
        />
      </BootstrapDialogHOC>

      <Button
        onClick={createIdeaDialog.handleClickOpen}
        type={"submit"}
        disabled={!lists || !edit}
        className="account-submit-btn account-submit-btn-outline add-idea-btn d-md-none"
      >
        Add idea to list
      </Button>
    </div>
  );
};

export default ListsPage;
