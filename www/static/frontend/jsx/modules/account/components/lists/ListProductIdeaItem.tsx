import React from "react";
import { ListItemMovableArea } from "@client/modules/account/components/lists/ListItemMovableArea";
import { ListProductItemBtns } from "@client/modules/account/components/lists/ListProductItemBtns";
import { EditIdea } from "@client/modules/account/components/lists/EditIdea";
import { ListProductItemComment } from "@client/modules/account/components/lists/ListProductItemComment";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@client/modules/account/components/lists/EditComment";
import { useDialog } from "../../hooks/useDialog";
import { MobileMenuForListItem } from "@client/modules/account/ts/types/MobileMenuForListItem";
import { MobileMenuForList } from "@client/modules/account/components/lists/MobileMenuForList";
import { useHistory } from "react-router-dom";

export const ListProductIdeaItem = ({
  info,
  drag,
  reorderProductList,
  index,
  listId,
  deleteItem,
  edit,
  onMoveClick,
}) => {
  const editCommentDialog = useDialog();

  const mobileMenuDialog = useDialog();

  const history = useHistory();

  const mobileDialogItems: MobileMenuForListItem[] = [
    {
      image: "/static/frontend/images/icons/account/idea-logo.svg",
      label: info.product.name,
    },
    {
      label: "Add comment, quantity & priority",
      onClick: () => {
        history.push(
          `/account/your-lists/edit-list-product-info/${listId}/${info.product_id}`
        );
      },
    },
    {
      label: "Move",
    },
    {
      label: "Delete",
      onClick: deleteItem,
    },
  ];

  return (
    <div className="product-list-item-container product-list-item-idea-container">
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
            listId={listId}
            info={info}
          />

          {edit &&
            (info.comment ? (
              <ListProductItemComment
                info={info}
                onEditCommentClick={editCommentDialog.handleClickOpen}
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
        deleteItem={deleteItem}
        onMoveClick={onMoveClick}
        id={info.product_id}
      />
      <MobileMenuForList
        items={mobileDialogItems}
        dialogOpen={mobileMenuDialog.open}
        dialogOnClose={mobileMenuDialog.handleClose}
      />
      <BootstrapDialogHOC
        show={editCommentDialog.open}
        title={"Edit comment, quantity & priority"}
        onClose={editCommentDialog.handleClose}
      >
        <EditComment
          onCloseClick={editCommentDialog.handleClose}
          listId={listId}
          productId={info.product_id}
          info={info}
        />
      </BootstrapDialogHOC>
    </div>
  );
};
