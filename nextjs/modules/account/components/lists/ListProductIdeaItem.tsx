import React from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import { ListProductItemBtns } from "@modules/account/components/lists/ListProductItemBtns";
import { ETheme } from "@modules/ui/forms/Button";
import { EditIdea } from "@modules/account/components/lists/EditIdea";
import { ListProductItemComment } from "@modules/account/components/lists/ListProductItemComment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { useDialog } from "../../hooks/useDialog";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { useRouter } from "next/router";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { List } from "@modules/account/ts/types/list.type";
import cn from "classnames";

import StylesListProductItems from "@modules/account/components/lists/ListProductItems.module.scss";
import Styles from "@modules/account/components/lists/ListProductIdeaItem.module.scss";

export const ListProductIdeaItem: React.FC<any> = (props) => {
  const { listItem, drag, reorderProductList, index, deleteItem, edit } = props;
  const editCommentDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const listInfo: List = useSelectorAccount((state) => state.lists.listView);
  const router = useRouter();
  const breakpoint = useBreakpoint();
  const deleteIdeaDialog = useDialog();
  const listView = useSelectorAccount((state) => state.lists.listView);
  const deleteIdea = () => {
    breakpoint({
      xs: () =>
        router.push(
          `/shopping-lists/actions/delete-product/idea/${listInfo.product_list_id}/${listItem.list_item_id}`
        ),
      md: deleteIdeaDialog.handleClickOpen,
    });
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: listItem.idea.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/add-comment/idea/${listInfo.product_list_id}/${listItem.list_item_id}`
        );
      },
    },
    {
      label: "Move",
      onClick: () => {
        router.push(
          `/shopping-lists/actions/move-product/idea/${listInfo.product_list_id}/${listItem.list_item_id}`
        );
      },
    },
    {
      label: "Delete",
      onClick: deleteIdea,
    },
  ];

  return (
    <div
      className={cn(
        StylesListProductItems.productListItemContainer,
        "product-list-item-container"
      )}
    >
      <div className="movable-area">
        {edit ? (
          <ListItemMovableArea
            onUpClick={() => reorderProductList(index, index - 1)}
            onDownClick={() => reorderProductList(index, index + 1)}
            drag={drag}
            index={index}
            length={listInfo.products.length}
          />
        ) : (
          <div className="product-list-item-movable-area-placeholder" />
        )}
      </div>

      <div className="product-list-item-info-content">
        <img
          className="product-image product-list-item-image"
          src={"/static/frontend/images/icons/account/idea-logo.svg"}
        />
        <div className="product-list-item-info">
          <EditIdea
            openMenuDialog={mobileMenuDialog.handleClickOpen}
            listId={listInfo}
            listItem={listItem}
            edit={edit}
          />

          {edit &&
            (listItem.comment ? (
              <ListProductItemComment
                listItem={listItem}
                onEditCommentClick={editCommentDialog.handleClickOpen}
                list={listInfo}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className={cn(Styles.editComment, "add-comment-text")}
              >
                Add comment, quantity & priority
              </div>
            ))}
        </div>
      </div>
      <ListProductItemBtns
        btnLabel={"search"}
        mainBtnType={ETheme.outlined}
        edit={edit}
        time={listItem.add_date}
        listId={listView.productListId}
        onMainBtnClick={() =>
          window.location.assign(`/search?q=${listItem.product.name}`)
        }
        handleDelete={deleteIdea}
        productId={listItem.list_item_id}
      />
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={
          listItem.comment
            ? "Edit comment, quantity & priority"
            : "Add comment, quantity & priority"
        }
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listView.productListId}
          list_item_id={listItem.list_item_id}
          info={listItem}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteIdeaDialog.open}
        title={"Confirm delete"}
        onClose={deleteIdeaDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteIdeaDialog.handleClose}
          onDeleteClick={() => {
            deleteItem();
            deleteIdeaDialog.handleClose();
          }}
          deleteType={"idea"}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
