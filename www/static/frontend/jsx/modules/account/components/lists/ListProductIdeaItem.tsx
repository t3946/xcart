import React from "react";
import { ListItemMovableArea } from "@client/modules/account/components/lists/ListItemMovableArea";
import { Tooltip } from "@client/modules/account/components/shared/Tooltip";
import { ProductStarsRating } from "@client/modules/account/components/shared/ProductStarsRating";
import { TooltipRatingContent } from "@client/modules/account/components/lists/TooltipRatingContent";
import { ListProductItemBtns } from "@client/modules/account/components/lists/ListProductItemBtns";
import { EditIdea } from "@client/modules/account/components/lists/EditIdea";
import { ListProductItemComment } from "@client/modules/account/components/lists/ListProductItemComment";
import BootstrapDialogHOC from "@client/modules/account/hoc/BootstrapDialogHOC";
import { EditComment } from "@client/modules/account/components/lists/EditComment";
import { useDialog } from "../../hooks/useDialog";

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

  return (
    <div className="product-list-item-container product-list-item-idea-container">
      {edit ? (
        <ListItemMovableArea
          onUpClick={() => reorderProductList(index, index - 1)}
          onDownClick={() => reorderProductList(index, index + 1)}
          drag={drag}
        />
      ) : (
        <div className="product-list-item-movable-area-placeholder" />
      )}

      <img
        className="product-image product-list-item-image"
        src={"/static/frontend/images/icons/account/idea-logo.svg"}
      />
      <div className="product-list-item-info">
        <EditIdea listId={listId} info={info} />

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

      <ListProductItemBtns
        btnLabel={"search"}
        mainBtnClasses={"account-submit-btn-outline"}
        edit={edit}
        deleteItem={deleteItem}
        onMoveClick={onMoveClick}
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
