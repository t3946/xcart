import React from "react";
import { ListItemMovableArea } from "@modules/account/components/lists/ListItemMovableArea";
import { ListProductItemBtns } from "@modules/account/components/lists/ListProductItemBtns";
import { EditIdea } from "@modules/account/components/lists/EditIdea";
import { ListProductItemComment } from "@modules/account/components/lists/ListProductItemComment";
import BootstrapDialogHOC from "@modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@modules/account/components/lists/EditComment";
import { useDialog } from "../../hooks/useDialog";
import { MobileMenuForListItem } from "@modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@modules/account/components/lists/MobileMenuForList";
import { useRouter } from "next/router";
import { ListProductItemProps } from "@modules/account/ts/types/list-product-item-props.type";
import { ConfirmDelete } from "@modules/account/components/lists/ConfirmDelete";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

export const ListProductIdeaItem: React.FC<ListProductItemProps> = ({
  info,
  drag,
  reorderProductList,
  index,
  listInfo,
  deleteItem,
  edit,
  onMoveClick,
}) => {
  const editCommentDialog = useDialog();
  const mobileMenuDialog = useDialog();
  const router = useRouter();
  const breakpoint = useBreakpoint();
  const deleteIdeaDialog = useDialog();

  const deleteIdea = () => {
    breakpoint({
      xs: () =>
        router.push(
          `/shopping-lists/delete-product/idea/${listInfo.product_list_id}/${info.product_id}/`
        ),
      md: deleteIdeaDialog.handleClickOpen,
    });
  };

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: info.product.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        router.push(
          `/shopping-lists/edit-list-product-info/${listInfo.cache_url}/${info.product_id}`
        );
      },
    },
    {
      label: "Move",
      onClick: () => {
        router.push(
          `/shopping-lists/move-product/${info.product_id}/${listInfo.product_list_id}`
        );
      },
    },
    {
      label: "Delete",
      onClick: deleteIdea,
    },
  ];

  return (
    <div className="product-list-item-container ">
      <div className="movable-area">
        {edit ? (
          <ListItemMovableArea
            onUpClick={() => reorderProductList(index, index - 1)}
            onDownClick={() => reorderProductList(index, index + 1)}
            drag={drag}
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
            listId={listInfo.product_list_id}
            info={info}
            edit={edit}
          />

          {edit &&
            (info.comment ? (
              <ListProductItemComment
                info={info}
                onEditCommentClick={editCommentDialog.handleClickOpen}
                listInfo={listInfo}
              />
            ) : (
              <div
                onClick={editCommentDialog.handleClickOpen}
                className="add-comment-text"
              >
                Add comment, quantity & priority
              </div>
            ))}
        </div>
      </div>
      <ListProductItemBtns
        btnLabel={"search"}
        mainBtnClasses={"account-submit-btn-outline"}
        edit={edit}
        deleteItem={deleteIdea}
        onMoveClick={onMoveClick}
        id={info.product_id}
        time={info.add_date}
        listId={listInfo.product_list_id}
        onMainBtnClick={() =>
          window.location.assign(`/search?q=${info.product.name}`)
        }
      />
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={
          info.comment
            ? "Edit comment, quantity & priority"
            : "Add comment, quantity & priority"
        }
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listInfo.product_list_id}
          productId={info.product_id}
          info={info}
        />
      </BootstrapDialogHOC>
      <BootstrapDialogHOC
        show={deleteIdeaDialog.open}
        title={"Confirm delete"}
        onClose={deleteIdeaDialog.handleClose}
      >
        <ConfirmDelete
          onCancelClick={deleteIdeaDialog.handleClose}
          onDeleteClick={deleteItem}
          deleteType={"idea"}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
