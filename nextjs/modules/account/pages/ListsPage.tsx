import React from "react";
import Button, { ETheme } from "@modules/ui/forms/Button";
import { Sceleton } from "@modules/shared/components/sceleton/Sceleton";
import { ListProductItemSkeleton } from "../components/lists/ListProductItemSkeleton";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { AddIdea } from "@modules/account/components/lists/AddIdea";
import { useDialog } from "@modules/account/hooks/useDialog";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import { ViewLists } from "@modules/account/components/lists/view-lists/ViewLists";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";
import cn from "classnames";
import StylesInnerPage from "@components/common/inner-page/InnerPage.module.scss";

interface IProps {
  list: any;
}

const ListsPage: React.FC<IProps> = (props) => {
  const { list } = props;
  const router = useRouter();
  const { lists, loading } = useSelectorAccount((state) => state.lists);
  const createIdeaDialog = useDialog();
  const breakpoints = useBreakpoint();
  const user = useSelectorAccount((e) => e.user);

  if (!user) {
    return null;
  }

  function listIsEdit() {
    if (list.owner.user_id === user.user_id) {
      return UserPrivateVariantsEnum.EDIT;
    }

    if (
      list?.roles.find((role: any) => role.user.user_id === user.user_id)
        ?.role === UserPrivateVariantsEnum.EDIT
    ) {
      return UserPrivateVariantsEnum.EDIT;
    }

    return UserPrivateVariantsEnum.VIEW;
  }

  const { cache } = router.query;
  const edit = listIsEdit() !== UserPrivateVariantsEnum.VIEW;
  // todo: what it is
  // const isBase = source === ListSource.Default;
  const isBase = true;

  function addToListIdea() {
    if (!edit) {
      return null;
    }

    return null;

    return (
      <div className={StylesInnerPage.accountPageFooter}>
        <Button
          onClick={createIdeaDialog.handleClickOpen}
          theme={ETheme.outlined}
          className={cn("d-lg-block w-md-auto mx-md-auto mx-lg-0 w-md-auto", {
            "d-none": !cache || loading,
            "d-lg-none": loading,
          })}
        >
          Add idea to list
        </Button>
      </div>
    );
  }

  return (
    <div>
      {lists ? (
        // breakpoints({
        //   xs: cache ? (
        //     <ViewLists list={list} isShoppingList={isBase} />
        //   ) : (
        //     <ListMobileMenu />
        //   ),
        //   lg: !!list && <ViewLists list={list} isShoppingList={isBase} />,
        // })
        <ViewLists list={list} isShoppingList={isBase} />
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
          listHash={list.cache_url}
          onCancelBtnClick={createIdeaDialog.handleClose}
        />
      </BootstrapDialogHOC>

      {addToListIdea()}
    </div>
  );
};

export default ListsPage;
