import React from "react";
import { useRouter } from "next/router";
import { useDialog } from "@modules/account/hooks/useDialog";
import { ShareListDialog } from "@modules/account/components/lists/ShareListDialog";
import { ManageList } from "@modules/account/components/lists/ManageList";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { deleteList } from "@redux/actions/account-actions/ListsActions";
import { useDispatch } from "react-redux";
import { useSnackbar } from "@modules/account/hooks/useSnackbar";
import ShareIcon from "@modules/icon/components/account/share/ShareIcon";
import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import cn from "classnames";
import Link from "next/link";
import Arrow from "@modules/icon/components/font-awesome/arrow-left/Solid";

import Styles from "@modules/account/components/lists/ListHeader.module.scss";

interface IProps {
  list: any;
  isShoppingList: boolean;
}

export const ListHeader: React.FC<IProps> = (props) => {
  const snackbar = useSnackbar();
  const shareDialog = useDialog();
  const { list, isShoppingList } = props;
  const userId = useSelectorAccount((e) => e.user.user_id);

  function listIsEdit() {
    if (list.owner.user_id === userId) {
      return UserPrivateVariantsEnum.EDIT;
    }

    if (
      list?.roles.find((role) => role.user.user_id === userId)?.role ===
      UserPrivateVariantsEnum.EDIT
    ) {
      return UserPrivateVariantsEnum.EDIT;
    }
    return UserPrivateVariantsEnum.VIEW;
  }

  const edit = listIsEdit() !== UserPrivateVariantsEnum.VIEW;
  const manageListDialog = useDialog();
  const deleteListDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const router = useRouter();
  const dispatch = useDispatch();

  function handleDeleteList() {
    dispatch(
      deleteList({
        data: {
          product_list_id: list.product_list_id,
        },
      })
    );
    deleteListDialog.handleClose();
    snackbar.show(`${list.name} list deleted successfully`);
    router.replace(`/shopping-lists/`);
  }

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      label: "Manage list",
      onClick: () =>
        router.push(`/shopping-lists/action-list/manage-list/${list.cacheUrl}`),
    },
    {
      label: "Add idea",
      onClick: () =>
        router.push(`/shopping-lists/action-list/add-idea/${list.cacheUrl}`),
    },
    {
      label: "Share list with others",
      onClick: () =>
        router.push(`/shopping-lists/action-list/share-list/${list.cacheUrl}`),
    },
  ];
  const mobileItemDelete = {
    label: "Delete list",
    onClick: () =>
      router.push(`/shopping-lists/action-list/delete-list/${list.cacheUrl}`),
  };

  return (
    <div
      className={cn(
        "list-header-container",
        Styles.listHeaderContainer,
        "m-0",
        "flex-wrap",
        "flex-lg-nowrap"
      )}
    >
      <div
        className={cn(
          "d-md-none",
          "position-relative",
          "d-flex",
          Styles.mobileHeader,
          Styles.shoppingList__mobileHeader
        )}
      >
        <Link href="/shopping-lists/">
          <a
            className={cn(
              Styles.accountButton,
              "form-button",
              "form-button__outline",
              "w-auto",
              "fw-bold",
              "px-3"
            )}
          >
            <Arrow className={cn(Styles.accountButtonIcon, "me-2")} />
            Back
          </a>
        </Link>
      </div>
      <div
        className={cn(
          "mt-20",
          "mt-md-0",
          "col-md-12",
          "col-lg-auto",
          "list-header-left-side",
          "justify-content-md-center",
          "justify-content-lg-start",
          "position-relative",
          "w-md-100",
          "w-lg-auto",
          "mb-md-20",
          "mb-lg-0"
        )}
      >
        <Link href="/dashboard">
          <a
            className={cn(
              "d-none",
              "d-md-flex",
              "d-lg-none",
              Styles.accountButton,
              "form-button",
              "position-absolute",
              "form-button__outline",
              "w-auto",
              "fw-bold",
              "px-3"
            )}
          >
            <Arrow className={cn(Styles.accountButtonIcon, "me-2")} />
            Account
          </a>
        </Link>
        <img
          className="list-header-private-type-img"
          src={`/static/frontend/images/icons/account/list-${list.listType}.svg`}
        />
        <div
          className={cn(
            "list-header-name",
            Styles.listHeaderName,
            Styles.listHeader__name
          )}
        >
          {list.name}
        </div>
        {edit && (
          <span
            className={"ms-3 py-10 px-1 cursor-pointer d-lg-none"}
            onClick={mobileMenuDialog.handleClickOpen}
          >
            <img
              src={"/static/frontend/dist/images/icons/account/ellipsis.svg"}
            />
          </span>
        )}
      </div>

      <div
        className={cn(
          Styles.listHeaderRightSide,
          "d-flex",
          "flex-grow-1",
          "align-items-center"
        )}
      >
        <div className="list-header-actions flex-grow-1 ms-lg-5">
          {edit && (
            <>
              <div
                onClick={manageListDialog.handleClickOpen}
                className={cn(
                  Styles.istHeaderActions__listHeaderAction,
                  Styles.listHeaderAction
                )}
              >
                Manage list
              </div>
              <div
                onClick={deleteListDialog.handleClickOpen}
                className={cn(
                  Styles.istHeaderActions__listHeaderAction,
                  Styles.listHeaderAction,
                  Styles.listHeaderAction_red
                )}
              >
                Delete list
              </div>
            </>
          )}
        </div>
        {edit && (
          <div className="list-header-shared-block">
            <ShareIcon className="list-header-share-btn blue" />
            <div
              className={cn(Styles.listHeaderAction)}
              onClick={shareDialog.handleClickOpen}
            >
              Share list with others
            </div>
          </div>
        )}
      </div>
      <ShareListDialog
        open={shareDialog.open}
        handleClose={shareDialog.handleClose}
        list={list}
      />
      <BootstrapDialogHOC
        show={manageListDialog.open}
        title={"Manage list"}
        onClose={manageListDialog.handleClose}
      >
        <ManageList list={list} onCancelClick={manageListDialog.handleClose} />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteListDialog.open}
        title={"Confirm delete list"}
        onClose={deleteListDialog.handleClose}
      >
        <ConfirmDelete
          deleteType="list"
          onDeleteClick={handleDeleteList}
          onCancelClick={deleteListDialog.handleClose}
        />
      </BootstrapDialogHOC>
      <MobileMenuForList
        items={
          isShoppingList
            ? mobileDialogItems
            : [...mobileDialogItems, mobileItemDelete]
        }
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
    </div>
  );
};
