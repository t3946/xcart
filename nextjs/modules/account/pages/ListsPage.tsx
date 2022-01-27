import React from "react";
import { useSelector } from "react-redux";
import { Button } from "@material-ui/core";
import StoreInterface from "@modules/account/ts/types/store.type";
import { Sceleton } from "@modules/shared/components/sceleton/Sceleton";
import { ListProductItemSkeleton } from "../components/lists/ListProductItemSkeleton";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { AddIdea } from "@modules/account/components/lists/AddIdea";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { ViewLists } from "@modules/account/components/lists/view-lists/ViewLists";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { ListSource } from "@modules/account/ts/types/list.type";
import { ListMobileMenu } from "@modules/account/components/lists/ListMobileMenu";
import { useRouter } from "next/router";

const ListsPage: React.FC = () => {
  const router = useRouter();
  const lists = useSelectorAccount((state) => state.lists.lists);
  const createIdeaDialog = useDialog();
  const breakpoints = useBreakpoint();
  const { role, cacheUrl, source } = useSelectorAccount(
    (state) => state.lists.listView
  );
  const { cache } = router.query;
  const edit = role !== UserPrivateVariantsEnum.VIEW;
  const isBase = source === ListSource.Default;
  return (
    <div>
      {lists ? (
        breakpoints({
          xs: cache ? (
            <ViewLists isShoppingList={isBase} />
          ) : (
            <ListMobileMenu />
          ),
          lg: <ViewLists isShoppingList={isBase} />,
        })
      ) : (
        <React.Fragment>
          <div className="list-header-container">
            <Sceleton height={36} maxWidth={"100%"} />
          </div>

          {Array.from({ length: 3 }, (v, k) => k).map((value, index) => (
            <ListProductItemSkeleton key={index} />
          ))}
        </React.Fragment>
      )}
      <BootstrapDialogHOC
        show={createIdeaDialog.open}
        title={"Create a new idea"}
        onClose={createIdeaDialog.handleClose}
      >
        <AddIdea
          listHash={cacheUrl}
          onCancelBtnClick={createIdeaDialog.handleClose}
        />
      </BootstrapDialogHOC>

      <Button
        onClick={createIdeaDialog.handleClickOpen}
        type="submit"
        disabled={!edit}
        className="account-submit-btn account-submit-btn-outline add-idea-btn"
      >
        Add idea to list
      </Button>
    </div>
  );
};

export default ListsPage;
